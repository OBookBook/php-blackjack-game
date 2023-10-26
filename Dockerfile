FROM php:8.2-apache

# xdebug 環境構築
RUN pecl install xdebug && \
  docker-php-ext-enable xdebug
# Docker公式のComposer イメージ を使用
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Composerでパッケージ管理する為の環境構築
RUN apt-get update && \
  apt-get install sudo && \
  sudo apt update && \
  sudo apt install && \
  sudo apt-get install -y git

# FIXME: 以下、php_codesnifferを使いたいがなぜか実行されないようだ。。。。。。。。

# パッケージ  php_codesniffer を使うための設定
RUN composer global require --no-interaction "squizlabs/php_codesniffer=*" && \
  export PATH="$PATH:$HOME/.composer/vendor/bin"

# Docker 公式の Composer イメージ を使用
# https://qiita.com/yatsbashy/items/02bbbebbfe7e5a5976bc
