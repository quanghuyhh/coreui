# Run this script to install composer package before can run sail cmd
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

# Copy env file, install npm packages

# How role work:
- Role services will check list roles of user contain given role from middleware
- If does not have role -> 404 page

## Middleware:
- [RoleGate.php](app%2FHttp%2FMiddleware%2FRoleGate.php)

## Service:
- [RoleService.php](app%2FServices%2FRoleService.php)

# Theme:
- [coreui-free-bootstrap-admin-template-main](html%2Fcoreui-free-bootstrap-admin-template-main)

# Thêm file scss/css:
- Ví dụ sử dụng 2 file:
```
resources/scss/style.scss
resources/css/app.css
```

- Mở file vite để khai báo [vite.config.js](vite.config.js)
```
laravel({
    input: ['resources/css/app.css', 'resources/js/app.js', 'resources/scss/style.scss'],
    refresh: true,
}),
```

- Sử dụng:
Ví dụ màn hình đăng nhập (layout: resources/views/layout/guest.blade.php)
Thêm file css vào
```
@vite(['resources/scss/style.scss'])
```
