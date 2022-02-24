#!/bin/sh
# wait-for-postgres.sh

set -e

while [ ! -f /etc/ssl/certs/api.dev.hello-world.ovh.pem ]
do
    echo "Awaiting for certificate"
    sleep 2
done

echo "Certificate available"

nginx -g 'daemon off;'
