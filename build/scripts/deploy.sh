#!/usr/bin/env bash

source ${DTK_HOME}/lib/helm.sh

NAMESPACE=tagd

API_REGISTRY=docker.totallydev.com/tagd/api

if [[ ${ENV} == "production" ]]; then
  image_tag=${CI_COMMIT_TAG}
  release="api"
  kube_config=${KUBE_CONFIG_PROD}
  env_secret="api-env"
  migrations_secret="api-migrations-env"
  service_account_name="tagd-api"
else
  image_tag="${ENV}-${CI_PIPELINE_ID}"
  release="api-${ENV}"
  kube_config=${KUBE_CONFIG_UAT}
  env_secret="api-env-${ENV}"
  migrations_secret="api-env-${ENV}"
  service_account_name=""
fi

set_chart_values () {
  # images
  set_chart_value image.apiProxy "${API_REGISTRY}/proxy:${image_tag}"
  set_chart_value image.api "${API_REGISTRY}/phpfpm:${image_tag}"

  # secrets
  set_chart_value imagePullSecret.api "gitlab-pull-secret"

  # hosts
  set_chart_value host.api ${HOST}

  # secrets
  set_chart_value secret.api.env ${env_secret}
  set_chart_value secret.api.migrationsEnv ${migrations_secret}

  # service account - prod uses service accounts for iam role assignment
  if [[ ! -z ${service_account_name} ]]; then
    set_chart_value serviceAccountName ${service_account_name}
  fi

  # replica counts
  set_chart_value replicas.api ${REPLICAS}
  set_chart_value replicas.apiProxy ${REPLICAS}

  # other
  set_chart_value env ${ENV}
  set_chart_value jobId ${CI_JOB_ID}

  # run migrations
  set_chart_value migrations.run false
}

deploy () {
  export KUBECTRL_CONFIG=${kube_config}
  export HELM_CHART_REPO=${HELM_CHART_REPO}

  if ! helm_deploy "${release}" ${NAMESPACE}; then
    exit 1
  fi

  echo "--------------------------------------------------------------------------------------------"
  echo "Deployed to ${ENV}:"
  echo "--------------------------------------------------------------------------------------------"
  echo " * https://${HOST}"
  echo "--------------------------------------------------------------------------------------------"
}

set_chart_values

deploy
