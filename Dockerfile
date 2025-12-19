FROM nginx:alpine

# Xóa các file mặc định của Nginx
RUN rm -rf /usr/share/nginx/html/*

# Copy CHỈ nội dung bên trong thư mục frontend vào thư mục gốc của Nginx
COPY frontend/ /usr/share/nginx/html/

EXPOSE 80