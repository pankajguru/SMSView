#!/bin/sh


# Open Office
SOFFICE_PATH="/home/jorge/alfresco-share/openoffice/program"
SOFFICE_PORT="8100"
SOFFICEBIN=/home/jorge/alfresco-share/openoffice/program/soffice.bin
SOFFICE="$SOFFICEBIN -nofirststartwizard -nologo -headless -accept=socket,host=localhost,port=$SOFFICE_PORT;urp;StarOffice.ServiceManager"
SOFFICE_STATUS=""


ERROR=0

get_pid() {
    PID=""
    PIDFILE=$1
    # check for pidfile
    if [ -f $PIDFILE ] ; then
        PID=`cat $PIDFILE`
    fi
}

is_service_running() {
    PID=$1
    if [ "x$PID" != "x" ] && kill -0 $PID 2>/dev/null ; then
        RUNNING=1
    else
        RUNNING=0
    fi
    return $RUNNING
}

is_soffice_running() {
    pids=`ps ax | grep $SOFFICEBIN | grep -v grep | awk {'print $1'}`
    if [ -n "$pids" ]; then
        RUNNING=1
    else
        RUNNING=0
    fi

    if [ $RUNNING -eq 0 ]; then
        SOFFICE_STATUS="openoffice not running"
    else
        SOFFICE_STATUS="openoffice already running"
    fi
    return $RUNNING
}

start_soffice() {
    is_soffice_running
    RUNNING=$?

    if [ $RUNNING -eq 1 ]; then
        echo "$0 $ARG: openoffice already running"
    else
        $SOFFICE >/dev/null 2>&1 &
        sleep 3
        is_soffice_running
        RUNNING=$?
        if [ $RUNNING -eq 0 ]; then
            ERROR=1
        fi
        if [ $ERROR -eq 0 ]; then
            echo "$0 $ARG: openoffice started at port $SOFFICE_PORT"
            sleep 2
        else
            echo "$0 $ARG: openoffice could not be started"
            ERROR=3
        fi
    fi
}

daemon_soffice() {
    $SOFFICE >/dev/null 2>&1
}

stop_soffice() {
    NO_EXIT_ON_ERROR=$1
    is_soffice_running
    RUNNING=$?

    if [ $RUNNING -eq 0 ]; then
        echo "$0 $ARG: $SOFFICE_STATUS"
        if [ "x$NO_EXIT_ON_ERROR" != "xno_exit" ]; then
            exit
        else
            return
        fi
    fi
    pids=`ps ax | grep $SOFFICEBIN | grep -v grep | awk {'print $1'}`
    if kill $pids ; then
	echo "$0 $ARG: openoffice stopped"
    else
	echo "$0 $ARG: openoffice could not be stopped"
	ERROR=4
    fi
}


if [ "x$1" = "xstart" ]; then
    start_soffice
elif [ "x$1" = "xdaemon" ]; then
    daemon_soffice
elif [ "x$1" = "xstop" ]; then
    stop_soffice
elif [ "x$1" = "xstatus" ]; then
    is_soffice_running
    echo "$SOFFICE_STATUS"
fi

exit $ERROR
