<?php
// app/core/Router.php

class Router
{
    protected $routes = []; // Array untuk menyimpan semua rute yang terdaftar

    /**
     * Menambahkan rute baru ke daftar rute.
     * @param string $method Metode HTTP (GET, POST, PUT, DELETE)
     * @param string $uri URI yang cocok (misal: '/', '/users/{id}')
     * @param array $action Array berisi nama Controller dan nama method (misal: ['HomeController', 'index'])
     */
    public function add($method, $uri, $action)
    {
        // Mengubah URI menjadi regex untuk mendukung parameter dinamis seperti {id}
        $uri = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $uri);
        $this->routes[] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    /**
     * Mendispatch (menjalankan) rute yang cocok dengan permintaan saat ini.
     */
    public function dispatch()
    {
        // Mendapatkan URI dari permintaan saat ini (misal: /users/1)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Mendapatkan metode HTTP dari permintaan saat ini (misal: GET, POST)
        $method = $_SERVER['REQUEST_METHOD'];

        // Iterasi melalui semua rute yang terdaftar
        foreach ($this->routes as $route) {
            // Memeriksa apakah metode HTTP cocok
            if ($route['method'] === $method) {
                // Mencocokkan URI dengan regex rute
                if (preg_match("#^" . $route['uri'] . "$#", $uri, $matches)) {
                    // Hapus elemen pertama ($matches[0]) yang berisi string URI lengkap
                    array_shift($matches);
                    $params = $matches; // Parameter yang diekstrak dari URI

                    // Memanggil action (Controller dan method) yang sesuai
                    list($controller, $method) = $route['action'];

                    // Memastikan file controller dimuat sebelum membuat instance
                    $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                    } else {
                        // Jika controller tidak ditemukan, log error atau tampilkan 500
                        error_log("Controller file not found: " . $controllerFile);
                        header("HTTP/1.0 500 Internal Server Error");
                        echo "500 - Internal Server Error";
                        return;
                    }

                    // Membuat instance dari Controller
                    $controllerInstance = new $controller();

                    // Memanggil method pada instance Controller dengan parameter
                    if (method_exists($controllerInstance, $method)) {
                        call_user_func_array([$controllerInstance, $method], $params);
                        return; // Menghentikan eksekusi setelah rute ditemukan dan dijalankan
                    } else {
                        error_log("Method {$method} not found in controller {$controller}");
                        header("HTTP/1.0 500 Internal Server Error");
                        echo "500 - Internal Server Error";
                        return;
                    }
                }
            }
        }

        // Jika tidak ada rute yang cocok, tampilkan halaman 404
        header("HTTP/1.0 404 Not Found");
        echo "404 - Halaman Tidak Ditemukan";
    }
}