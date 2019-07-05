#!/usr/bin/env bash

# Input point ----------------------------------------------

readonly CONFIG="/etc/roadrunner/.rr.local.yml"
readonly RELATIVE_PATH=`dirname $0`

echo $0
main() {
  local rule
  if [[ $# -eq 0 ]]; then
   rule=""
  else
    rule="$1"
    shift
  fi
  local args="$@"

  case "$rule" in
    reset-workers | rw )  rr -c ${CONFIG} http:reset; exit ;;
    show-workers | sw )  rr -c ${CONFIG} http:workers -i; exit ;;
    watch )  ${RELATIVE_PATH}/watch.sh; exit ;;
    * )                   usage; exit 1 ;;
  esac
}

main "$@"


