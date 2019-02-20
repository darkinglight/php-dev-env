# 基于docker的php开发环境

### 安装docker
1. 下载地址(https://www.docker.com/community-edition)

### 安装daocloud镜像加速器
推荐daocloud的加速器[https://www.daocloud.io/mirror#accelerator-doc]
注册后根据系统执行以下命令即可。
## linux
`curl -sSL https://get.daocloud.io/daotools/set_mirror.sh | sh -s http://428db162.m.daocloud.io`
该脚本可以将 --registry-mirror 加入到你的 Docker 配置文件 /etc/docker/daemon.json 中。适用于 Ubuntu14.04、Debian、CentOS6 、CentOS7、Fedora、Arch Linux、openSUSE Leap 42.1，其他版本可能有细微不同。更多详情请访问文档。
## mac windows
在桌面右下角状态栏中右键 docker 图标，修改在 Docker Daemon 标签页中的 json ，把下面的地址:
http://xxxxx.m.daocloud.io
加到"registry-mirrors"的数组里。点击 Apply 。

### 拉取镜像
1. 安装基础镜像，docker pull debian:jessie
2. 安装php-fpm,默认为php7：docker pull php:7-fpm
3. 安装nginx，docker pull nginx
4. 安装mysql docker pull mysql

### 使用docker-compose启动php开发环境
1. 修改docker-compose.yml的项目路径为自己的项目路径，数据库名，数据库用户名，数据库密码为需要的值
2. 调整host的域名解析 在本地hosts文件加上127.0.0.1 test
2. 在项目中做相应调整，mysql连接host使用mysql，指向mysql容器，如：'dsn' => 'mysql:host=mysql;dbname=test'， 如果需要在本地执行mysql相关脚本，如migration，则需要连接mysql在宿主机上的映射端口，默认宿主机ip：127.0.0.1，port：3306，所以连接参数为'dsn' => 'mysql:host=127.0.0.1;dbname=roman'
3. docker-compose up,关闭时执行CTR+C即可
4. 如果不需要监控，可以后台启动：docker-compose -d up, 关闭时执行：docker-compose stop
5. redis命令的执行: docker exec -it redis redis-cli
6. mysql命令的执行: docker exec -it mysql mysql -h127.0.0.1 -udocker -pdocker
7. composer命令的执行: 比较麻烦，需要将本地的文件mount到docker容器中，进入swoole容器执行composer install

## 其他

### 使用Dockerfile
1. 进入php,执行docker build -t light/php:v1 .
2. 进入nginx,执行docker build -t light/nginx:v1 .
3. docker run -d --name mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=qsq -e MYSQL_PASSWORD=123456 -e MYSQL_DATABASE=qsq_erp mysql
4. docker run -d --name phpfpm -v path/to/web:/data/www/web --link mysql:mysql light/php:v1
5. docker run -d --name nginx -p 80:80 --volumes-from phpfpm --link phpfpm:phpfpm light/nginx:v1

### MYSQL的使用
1. docker run --name mymysql -v /Users/chenyuejun/docker/mysql/sql:/docker-entrypoint-initdb.d -v /Users/chenyuejun/docker/mysql/conf.d:/etc/mysql/conf.d -e MYSQL_ROOT_PASSWORD=light -d mysql
2. docker run -it --rm --link mymysql:mysql mysql sh -c exec mysql -h"$MYSQL_PORT_3306_TCP_ADDR" -P"$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD"
3. docker exec -it mymysql bash
4. docker exec some-mysql sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/all-databases.sql
5. 找出僵尸volume并删除： docker volume ls -f dangling=true；    docker volume rm volume_name
