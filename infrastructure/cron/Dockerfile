FROM ddp/api

# Declare build args
ARG app_env

# Install the cron package
RUN apt-get update && apt-get -y install cron

# Copy the crontab in a location where it will be parsed by the system
COPY ./infrastructure/cron/crontab /etc/cron.d/crontab

# Owner can read and write into the crontab, group and others can read it
RUN chmod 0644 /etc/cron.d/crontab

# Run the crontab using the binary from the package we installed
RUN /usr/bin/crontab /etc/cron.d/crontab

# Run the command on container startup
CMD ["cron", "-f"]
