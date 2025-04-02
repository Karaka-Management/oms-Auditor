# Audit

Audit logs contian the following information:

## Action

What kind of action is responsible for the creation of this audit log:

1. Create - data creation
2. Read - data was read (very rarely used)
3. Update - data updated
4. Delete - data deleted (includes soft deletes)
5. Unknown - unknown

## Type

Which data type is associated with the audit log. This number is only for internal purposes.

## Trigger

Trigger describing what caused the audit log.

## By

Who created the audit log. Internal actions are assigned to the admin account.

## Ref

Reference identifier to the data (e.g. news id, client id, address id, ...).

## Date

Date of the audit log creation.

## Module

Which module is responsible for creating the audit log.

## IP

Which IP address is responsible for creating the audit log. Internal audit logs are attributed to 127.0.0.1.

## Data

The audit log shows what kind of data change was performed. For `Create` statements no data is logged, since the data stored in the database itself already logs the current state of the data.

![General Settings](Modules/Auditor/Docs/Help/img/audit.png)