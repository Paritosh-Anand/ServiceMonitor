# ServiceMonitor
Thrift service monitoring from Zookeeper

## Environment brief
service oriented architecture: 
- micro services publishes there availability by creating znodes on zookeeper.
- Frontend keeps a watch on a zk path where these services registers themselves. Hence updates it's connection pool based on notifications sent by zookeeper.

Therefore there is a need to have a check on number of services that are actually available to front-end. This very purpose is solved by ServiceMonitor.

ServiceMonitor keeps a watch on the same zk path (as frontend tier does) and updates the monitoring dashboard on the required data.

ServiceMonitor relies on Zookeeper to send necessary notifications.
