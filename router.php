   <?php

    class Router
    {
        private $routerAdmin = [];
        private $router = [];
        public function add($page, $controller, $method)
        {
            $this->router[$page] = [
                'controller' => $controller,
                'method' => $method,
            ];
        }

        public function addRouterAdmin($admin, $controllerAdmin, $methodAdmin)
        {
            $this->routerAdmin[$admin] = [
                'controllerAdmin' => $controllerAdmin,
                'methodAdmin' => $methodAdmin,
            ];
        }

        public function dispatch()
        {
            $page = $_GET['page'] ?? 'index';
            $id = $_GET['id'] ?? null;
            if (!isset($this->router[$page])) {
                die("404 - Trang không tồn tại");
            }

            $controllerName = $this->router[$page]['controller'];
            $method = $this->router[$page]['method'];

            require_once __DIR__ . "/frontend/controller/$controllerName.php";

            $controller = new $controllerName();

            if (!method_exists($controller, $method)) {
                die("Method không tồn tại");
            }

            if ($id !== null) {
                $controller->$method($id);
            } else {
                $controller->$method();
            }
        }

        public function dispatchAdmin()


        {

            $admin = $_GET['admin'] ?? 'index';
            $idAdmin = $_GET['idAdmin'] ?? null;

            if (!isset($this->routerAdmin[$admin])) {
                die("404 - Trang không tồn tại");
            }

            $controllerName = $this->routerAdmin[$admin]['controllerAdmin'];
            $method = $this->routerAdmin[$admin]['methodAdmin'];

            require_once __DIR__ . "/backend/controllers/$controllerName.php";

            $controller = new $controllerName();

            if (!method_exists($controller, $method)) {
                die("Method không tồn tại");
            }


            if ($idAdmin !== null) {
                $controller->$method($idAdmin);
            } else {
                $controller->$method();
            }
        }
    }
