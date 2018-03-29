#!/bin/bash
#
#stabilok backup
#�����:
#������ � ����� ���������� �� �����
#
#ID: bac68bf1289f48dc8ff832db32ff916e
#������: d6add9520b8c4a9486a3edd852bd092f
#Callback URL: https://oauth.yandex.ru/verification_code
#
#����� AQAAAAAAOd3wAANfDC_IZTtYHUzErr5sGL0nlOk
#

# ---------- SETTINGS BACKUP MYSQL-------------
#��� ��� ����� ������ ����� ����, ����� ��� ����� ��� ��� ���� mysql ������ �������� ��� �������
USER=root

#������ ������������ ���� ������ (������ �� ���� ������� � �� ���� mysql ������ �� �������)
PASSWORD=1965

#����� ��� ���������� �������, ����� �������� �� ������ ���� ����� �������
BACKUP=/var/www/backup_tmp

#������� ��� ��������� ��� ������ �� ������ � ��� ����� �� ������ �����
PREFIX=stabilok.ru

#���������� ���� ��� �������� ������ ������������ � ����� ������
DATE=`date '+%Y-%m-%d'`

# ---------- SETTINGS BACKUP FILES-------------
#����� ������ ��������� ����� ��� ���������, ��� ��� ����� �������� � ���� ����� � ���������� �� ������ ����
DIRECTORY="/var/www/stabilok.ru"

# ---------- SETTINGS YANDEX API-------------
# Yandex.Disk ����� - ��� ��� �������� ��� �� omelchuck.ru
TOKEN='AQAAAAAAOd3wAANfDC_IZTtYHUzErr5sGL0nlOk'

# ��� ����� ����� ����� ��� ������ ���� �� ���� � ������, �� ���� � ���� ������� ������ � ���������� BACKUP
LOGFILE=stabilok_backup.log

#���� ���������� ���������� � ���������� ������� / �������� ������ ���� �� ���� ���������� ������ ��� ������� ��� �����
mailLog='stan1010@ya.ru'

#���������� ������ ������, ���� ����� false �� ����� ������������ ��� � ������� ����
mailLogErrorOnly=false

# ---------- FUNCTIONS ------------
function mailing()
{
    # Function's arguments:
    # $1 -- email subject
    # $2 -- email body
    if [ ! $mailLog = '' ];then
        if [ "$mailLogErrorOnly" == true ];
        then
            if echo "$1" | grep -q 'error'
            then   
                echo "$2" | mail -s "$1" $mailLog > /dev/null
            fi
        else
            echo "$2" | mail -s "$1" $mailLog > /dev/null
        fi
    fi
}

function logger()
{
    echo "["`date "+%Y-%m-%d %H:%M:%S"`"] File $BACKUP: $1" >> $BACKUP/$LOGFILE
}

function parseJson()
{
    local output
    regex="(\"$1\":[\"]?)([^\",\}]+)([\"]?)"
    [[ $2 =~ $regex ]] && output=${BASH_REMATCH[2]}
    echo $output
}

function checkError()
{
    echo $(parseJson 'error' "$1")
}

function getUploadUrl()
{
    json_out=`curl -s -H "Authorization: OAuth $TOKEN" https://cloud-api.yandex.net:443/v1/disk/resources/upload/?path=app:/$backupName&overwrite=true`
    #echo $json_out
    json_error=$(checkError "$json_out")
    if [[ $json_error != '' ]];
    then
        logger "Yandex Disk error: $json_error"
        mailing "Yandex Disk backup error" "ERROR copy file $FILENAME. Yandex Disk error: $json_error"
	echo ''
	#exit 1
    else
        output=$(parseJson 'href' $json_out)
        echo $output
    fi
}

function uploadFile
{
    local json_out
    local uploadUrl
    local json_error
    uploadUrl=$(getUploadUrl)
    if [[ $uploadUrl != '' ]];
    then
	echo $UploadUrl
        json_out=`curl -s -T $1 -H "Authorization: OAuth $TOKEN" $uploadUrl`
        json_error=$(checkError "$json_out")
    if [[ $json_error != '' ]];
    then
        logger "Yandex Disk error: $json_error"
        mailing "Yandex Disk backup error" "ERROR copy file $FILENAME. Yandex Disk error: $json_error"

    else
        logger "Copying file to Yandex Disk success"
        mailing "Yandex Disk backup success" "SUCCESS copy file $FILENAME"

    fi
    else
    echo 'Some errors occured. Check log file for detail'
        #exit 1
    fi
}

logger "-------------------------START BACKUP $DATE-----------------------"
logger "��������� ����� ���"
mkdir $BACKUP/$DATE
for i in `mysql -u $USER -p$PASSWORD -e'show databases;' | grep -v information_schema | grep -v Database`;
    do mysqldump -u $USER -p$PASSWORD $i > $BACKUP/$DATE/$i.sql;
done

logger "������� ����� mysql $BACKUP/$DATE-$PREFIX.tar.gz"
tar -czf $BACKUP/$DATE-$PREFIX.tar.gz $BACKUP/$DATE
rm -rf $BACKUP/$DATE

logger "������� ����� ��������� $BACKUP/$DATE-files-$PREFIX.tar.gz"
tar -czf $BACKUP/$DATE-files-$PREFIX.tar.gz $DIRECTORY

FILENAME=$DATE-$PREFIX.tar.gz
logger "��������� �� ������ ���� ����� mysql $BACKUP/$DATE-$PREFIX.tar.gz"
backupName=$DATE-$PREFIX.tar.gz
uploadFile $BACKUP/$DATE-$PREFIX.tar.gz

FILENAME=$DATE-files-$PREFIX.tar.gz
logger "��������� �� ������ ���� ����� � ������� $BACKUP/$DATE-files-$PREFIX.tar.gz"
backupName=$DATE-files-$PREFIX.tar.gz
uploadFile $BACKUP/$DATE-files-$PREFIX.tar.gz

logger "������� ������ � �����"
find $BACKUP -type f -name "*.gz" -exec rm '{}' \;

logger "������ ��������"