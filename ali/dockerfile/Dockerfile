#Version: 0.0.1
FROM ubuntu:14.04
MAINTAINER Kyrie kyrieup@gmail.com
ENV REFRESHED_AT 2017-03-04
RUN apt-get -qq  update
RUN apt-get install -y nginx
RUN echo '哈哈,my boy:)' > /usr/share/nginx/html/index.html
EXPOSE 80