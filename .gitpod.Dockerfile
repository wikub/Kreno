FROM gitpod/workspace-full:2022-06-20-19-54-55

RUN sudo update-alternatives --set php $(which php7.4)

RUN sudo apt-get update
RUN sudo apt-get install -y --no-install-recommends \
        locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip libpq-dev nodejs npm wget \
        apt-transport-https lsb-release ca-certificates

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
RUN sudo apt install symfony-cli

RUN sudo install-packages php7.4-{zip,bz2,intl,gd,mbstring,mysql,pdo-mysql,pdo-sqlite,pdo-pgsql}

RUN composer install

RUN yarn install