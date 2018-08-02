FROM silintl/php7
MAINTAINER Your Name <your_email@domain.com>

ENV REFRESHED_AT 2015-05-11

# Copy an Apache vhost file into sites-enabled. This should map
# the document root to whatever is right for your app
COPY vhost-config.conf /etc/apache2/sites-enabled/

#Install vi editor
RUN apt-get update && apt-get install -y vim
RUN adduser admin
RUN mkdir -p /home/admin/app
#VOLUME ["/home/app/app"]

# Copy your application source into the image
#COPY app/ /home/admin/app/

#Install vi editor

WORKDIR /home/admin/app
EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]