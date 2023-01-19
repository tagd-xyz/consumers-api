#!/usr/bin/env bash

PRIMARY_CONTAINER=tagd-api

install_docker_toolkit () {
  export DTK_VERSION=1.5.14
  export DTK_HOME=~/.dtk/${DTK_VERSION}
  curl -s https://dtk.totallydev.com/install.sh | bash 2>/dev/null
  if [[ ! -d ${DTK_HOME} ]]; then
    echo "Dev toolkit not installed"
    exit 1
  fi
  source ${DTK_HOME}/lib/dev.sh 1
}

install_docker_toolkit

echo "Stopping services"
docker-compose -p ${PRIMARY_CONTAINER} down
