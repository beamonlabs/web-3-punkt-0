## create data volume
```
> docker volume create --name web30_data
```

## backup from data volume
```
> docker run --rm --volumes-from web3punkt0_mysql_1 -v (pwd):/backup bash tar cvf /backup/mysql-backup.tar -C /var/lib/mysql .
```

## restore from backup to data volume
```
> docker run --rm -v web30_data:/var/lib/mysql -v (pwd):/backup bash tar xvf /backup/mysql-backup.tar -C /var/lib/mysql
```
