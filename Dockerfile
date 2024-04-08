FROM nginx:latest

COPY conf/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 8080

ENTRYPOINT /bin/sh -c "nginx -g 'daemon off;'"
