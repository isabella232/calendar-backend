FROM nginx:latest

EXPOSE 8080

ENTRYPOINT /bin/sh -c "nginx -g 'daemon off;'"
