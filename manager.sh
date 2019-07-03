#!/usr/bin/env bash

# Written with best practices:
# - https://www.davidpashley.com/articles/writing-robust-shell-scripts/
# - https://github.com/progrium/bashstyle

[[ "$TRACE" ]] && set -o xtrace # For debugging. Print command traces before executing command.
set -o errexit # Tells bash that it should exit the script if any statement returns a non-true return value
set -o nounset # Exit script if you try to use an uninitialised variable
set -o pipefail # false | true will be considered to have fail
set -o noclobber # IO redirection

alias errcho=">&2 echo"

readonly SCRIPT_NAME="Bash Manager"

# File names
readonly LARAVEL_ENV_LARAVEL_FILENAME=".env.laravel"
readonly LARAVEL_ENV_EXAMPLE_FILENAME=".env.example"
readonly LARAVEL_ENV_FILENAME=".env"
readonly LARADOCK_ENV_FILENAME=".env"
readonly LARADOCK_ENV_EXAMPLE_FILENAME="env-example"
readonly _LARADOCK_ENV_FILENAME=".env"
readonly _LARADOCK_ENV_EXAMPLE_FILENAME="env-example"

# Directory names
readonly DATA_DIRNAME="data"
readonly SRC_DIRNAME="src"
readonly LARADOCK_DIRNAME="laradock"
readonly _LARADOCK_DIRNAME=".laradock"

# Paths
readonly INIT_DIRPATH=$(pwd)
readonly DATA_DIRPATH=${INIT_DIRPATH}/${DATA_DIRNAME}
readonly SRC_DIRPATH=${INIT_DIRPATH}/${SRC_DIRNAME}
readonly LARADOCK_DIRPATH=${INIT_DIRPATH}/${LARADOCK_DIRNAME}
readonly _LARADOCK_DIRPATH=${INIT_DIRPATH}/${_LARADOCK_DIRNAME}

readonly LARAVEL_ENV_FILEPATH=${SRC_DIRPATH}/${LARAVEL_ENV_FILENAME}
readonly LARAVEL_ENV_LARAVEL_FILEPATH=${INIT_DIRPATH}/${LARAVEL_ENV_LARAVEL_FILENAME}
readonly LARAVEL_ENV_EXAMPLE_FILEPATH=${SRC_DIRPATH}/${LARAVEL_ENV_EXAMPLE_FILENAME}
readonly LARADOCK_ENV_FILEPATH=${LARADOCK_DIRPATH}/${LARADOCK_ENV_FILENAME}
readonly LARADOCK_ENV_EXAMPLE_FILEPATH=${LARADOCK_DIRPATH}/${LARADOCK_ENV_EXAMPLE_FILENAME}
readonly _LARADOCK_ENV_FILEPATH=${_LARADOCK_DIRPATH}/${_LARADOCK_ENV_FILENAME}
readonly _LARADOCK_ENV_EXAMPLE_FILEPATH=${_LARADOCK_DIRPATH}/${_LARADOCK_ENV_EXAMPLE_FILENAME}



readonly LARADOCK_REPO="git@github.com:laradock/laradock.git"
readonly LARADOCK_REPO_TAG="v7.15"

readonly DOCKER_SERVICES="nginx postgres workspace"

readonly WORKSPACE_SERVICE_NAME="workspace"
readonly PRIMARY_DB_SERVICE_NAME="postgres"

readonly PHP_DEBUG_MODE="php -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=172.17.0.1"

readonly DB_USER=$([[ -f ${LARADOCK_ENV_FILEPATH} ]] && cat ${LARADOCK_ENV_FILEPATH} | grep -m1 DB_USER | cut -d'=' -f2)
readonly DB_DB=$([[ -f ${LARADOCK_ENV_FILEPATH} ]] && cat ${LARADOCK_ENV_FILEPATH} | grep -m1 DB_DATABASE | cut -d'=' -f2)

# Init in parse_args function
declare -A PARSED_ARGS

declare HELP_TEXT="
Usage: ./$(basename "$0") command [OPTION]...

    init [OPTION]...             If you will run it without options, all of options will be launched
         OPTIONS:
         -ld                     Downloads laradock
         -dc                     Setup docker-compose services
         -ll                     Makes Laravel initial commands (copy .env from .env.example, key:generate, migrate)
         -p                      Runs passport:install

    workspace | ws [OPTION]...   Runs workspace docker container under root user
         OPTIONS:                Use any option or command for workspace container

    artisan | art [OPTION]...         Launches artisan inside workspace docker container under laradock user
         OPTIONS:                     Use any option or command for artisan

    artisan_debug | artd [OPTION]...  The same as above, but launches php in debug mode.
                                      Edit PHP_DEBUG_MODE variable if you need another configurations

    composer | cmp [OPTION]...   Launches Composer inside workspace docker container under laradock user
         OPTIONS:                Use any option or command for Composer

    npm [OPTION]...              Launches NPM inside workspace docker container under laradock user
         OPTIONS:                Use any option or command for NPM

    mysql | mql                  Connects to MySQL docker container

    dc | docker [OPTION]...      Runs docker-compose in laradock directory
         OPTIONS:                Use any option or command for docker-compose
                                 OR use next predefined options for most use-cases
         up                      up -d $DOCKER_SERVICES
         clear                   rm -s

    -h | --help                  Usage tips
"

# Helpers ----------------------------------------------

usage() {
	printf "You are using $SCRIPT_NAME\n $HELP_TEXT"
}

parse_args() {
  for i in "$@"
  do
    PARSED_ARGS["${i//-}"]=true;
    shift;
  done
}

print_parsed_args() {
  for i in "${!PARSED_ARGS[@]}"
  do
    echo "key  : $i"
    echo "value: ${PARSED_ARGS[$i]}"
  done
}

copy() {
  local src="$1"
  local dest="$2"

  echo "Copying ${src} to ${dest}"
  cp -avr -T ${src} ${dest}
}

abs_path() {
  local path="$1"

  echo ${INIT_DIRPATH}/${path}
}

does_configured() {
  local filename="$1"

  printf "Does ${filename} configured and ready to use? [y/n]: "
  read answer
  echo "${answer}"
  if [[ "${answer}" != "y" ]]; then
    echo "Script was stopped. Continue when ${filename} will be ready"
    exit
  fi
}

# Command boosters ----------------------------------------------

workspace_as_root() {
  local args="$1"

  cd ${LARADOCK_DIRPATH}
  docker_compose exec ${WORKSPACE_SERVICE_NAME} ${args}
}

workspace_as_laradock() {
  local args="$1"

  cd ${LARADOCK_DIRPATH}
  docker_compose exec --user=laradock ${WORKSPACE_SERVICE_NAME} ${args}
}

artisan() {
  local args="$1"
  workspace_as_laradock "php artisan ${args}"
}

artisan_debug() {
  local args="$1"
  workspace_as_laradock "${PHP_DEBUG_MODE} artisan ${args}"
}

composer() {
  local args="$1"
  workspace_as_laradock "composer ${args}"
}

npm() {
  local args="$1"
  workspace_as_laradock "npm ${args}"
}

docker_compose() {
  local f_arg="$1"
  local args="$@"


  case "$f_arg" in
      up )    args="up -d ${DOCKER_SERVICES}" ;;
      clear ) args="rm -s" ;;
  esac

  cd ${LARADOCK_DIRPATH}
  docker-compose ${args}
}

mysql() {
  docker_compose exec ${PRIMARY_DB_SERVICE_NAME} mysql -u ${DB_USER} -p ${DB_DB}
}

# Initializers ----------------------------------------------

init() {
  local all=false

  # if no args
  [[ -v PARSED_ARGS[@] ]] || all=true

  # all or -ld enabled
  (${all} || [[ -v "PARSED_ARGS[ld]" ]]) && init_laradock
  (${all} || [[ -v "PARSED_ARGS[dc]" ]]) && docker_compose up
  (${all} || [[ -v "PARSED_ARGS[ll]" ]]) && init_laravel
  (${all} || [[ -v "PARSED_ARGS[p]" ]]) && init_artisan_passport
}

init_laradock() {
  # clone laradock
  if [[ ! -d ${LARADOCK_DIRPATH} ]]; then
    git clone ${LARADOCK_REPO} ${LARADOCK_DIRPATH}
    git checkout tags/${LARADOCK_REPO_TAG}
  fi

  # prepare laradock environment file
  if [[ ! -f ${_LARADOCK_ENV_EXAMPLE_FILEPATH} ]]; then
    echo "${_LARADOCK_ENV_EXAMPLE_FILEPATH} not found. Copying ${LARADOCK_ENV_EXAMPLE_FILEPATH}"
    copy "${LARADOCK_ENV_EXAMPLE_FILEPATH}" "${_LARADOCK_ENV_EXAMPLE_FILEPATH}"
  fi

  if [[ ! -f ${_LARADOCK_ENV_FILEPATH} ]]; then
    copy "${_LARADOCK_ENV_EXAMPLE_FILEPATH}" "${_LARADOCK_ENV_FILEPATH}"
    does_configured ${_LARADOCK_ENV_FILEPATH}
  fi

  copy ${_LARADOCK_DIRNAME} ${LARADOCK_DIRPATH}

  if [[ ! -d "${DATA_DIRPATH}" ]]; then
    echo "Creating ${DATA_DIRPATH}"
    mkdir "${DATA_DIRPATH}"
  fi

  if [[ ! -d "${SRC_DIRPATH}" ]]; then
    echo "Creating ${SRC_DIRPATH}"
    mkdir "${SRC_DIRPATH}"
  fi
}

init_laravel() {
  # count files inside src
  local src_count_files=$(ls -l ${SRC_DIRPATH} | wc -l)

  if [[ "${src_count_files}" -lt "2" ]]; then
    # install laravel
    echo "Hello LARAVEL!"
    composer "create-project --prefer-dist laravel/laravel ${SRC_DIRNAME}"
  fi

  if [[ ! -f ${LARAVEL_ENV_LARAVEL_FILEPATH} ]]; then
    # Copy .env example from Laravel to project root
    copy "${LARAVEL_ENV_EXAMPLE_FILEPATH}" "${LARAVEL_ENV_LARAVEL_FILEPATH}"
  fi

  does_configured ${LARAVEL_ENV_LARAVEL_FILEPATH}
  copy "${LARAVEL_ENV_LARAVEL_FILEPATH}" "${LARAVEL_ENV_FILEPATH}"

  composer install
  artisan key:generate
  artisan migrate
}

init_artisan_passport() {
  artisan passport:install
}

# Input point ----------------------------------------------

main() {
  local rule
  if [[ $# -eq 0 ]]; then
   rule=""
  else
    rule="$1"
    shift
  fi
  local args="$@"

  parse_args ${args}

  case "$rule" in
    init )                init; exit ;;
    workspace | ws )      workspace_as_root "$args"; exit ;;
    artisan | art )       artisan "$args"; exit ;;
    artisan_debug | artd ) artisan_debug "$args"; exit ;;
    composer | cmp )      composer "$args"; exit ;;
    npm )                 npm "$args"; exit ;;
    mysql | mql )         mysql; exit ;;
    dc | docker )         docker_compose "$args"; exit ;;
    -h | --help )         usage; exit ;;
    * )                   usage; exit 1 ;;
  esac
}

main "$@"