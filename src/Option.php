<?php

namespace PtOscCommandGenerator;

use ReflectionClass;

abstract class Option
{
    public const ALTER_FOREIGN_KEYS_METHOD = 'alter-foreign-keys-method';
    public const ANALYZE_BEFORE_SWAP = 'analyze-before-swap';
    public const NO_ANALYZE_BEFORE_SWAP = 'no-analyze-before-swap';
    public const ASK_PASS = 'ask-pass';
    public const BINARY_INDEX = 'binary-index';
    public const CHANNEL = 'channel';
    public const CHARSET = 'charset';
    public const CHECK_ALTER = 'check-alter';
    public const NO_CHECK_ALTER = 'no-check-alter';
    public const CHECK_FOREIGN_KEYS = 'check-foreign-keys';
    public const NO_CHECK_FOREIGN_KEYS = 'no-check-foreign-keys';
    public const CHECK_INTERVAL = 'check-interval';
    public const CHECK_PLAN = 'check-plan';
    public const NO_CHECK_PLAN = 'no-check-plan';
    public const CHECK_REPLICATION_FILTERS = 'check-replication-filters';
    public const NO_CHECK_REPLICATION_FILTERS = 'no-check-replication-filters';
    public const CHECK_REPLICA_LAG = 'check-replica-lag';
    public const CHECK_SLAVE_LAG = 'check-slave-lag';
    public const CHUNK_INDEX = 'chunk-index';
    public const CHUNK_INDEX_COLUMNS = 'chunk-index-columns';
    public const CHUNK_SIZE = 'chunk-size';
    public const CHUNK_SIZE_LIMIT = 'chunk-size-limit';
    public const CHUNK_TIME = 'chunk-time';
    public const CONFIG = 'config';
    public const HISTORY_TABLE = 'history-table';
    public const CRITICAL_LOAD = 'critical-load';
    public const DATABASE = 'database';
    public const DEFAULT_ENGINE = 'default-engine';
    public const DATA_DIR = 'data-dir';
    public const REMOVE_DATA_DIR = 'remove-data-dir';
    public const DEFAULTS_FILE = 'defaults-file';
    public const DROP_NEW_TABLE = 'drop-new-table';
    public const NO_DROP_NEW_TABLE = 'no-drop-new-table';
    public const DROP_OLD_TABLE = 'drop-old-table';
    public const NO_DROP_OLD_TABLE = 'no-drop-old-table';
    public const DROP_TRIGGERS = 'drop-triggers';
    public const NO_DROP_TRIGGERS = 'no-drop-triggers';
    public const CHECK_UNIQUE_KEY_CHANGE = 'check-unique-key-change';
    public const NO_CHECK_UNIQUE_KEY_CHANGE = 'no-check-unique-key-change';
    public const FORCE = 'force';
    public const HELP = 'help';
    public const HISTORY = 'history';
    public const HOST = 'host';
    public const MAX_FLOW_CTL = 'max-flow-ctl';
    public const MAX_LAG = 'max-lag';
    public const MAX_LOAD = 'max-load';
    public const PRESERVE_TRIGGERS = 'preserve-triggers';
    public const NEW_TABLE_NAME = 'new-table-name';
    public const NULL_TO_NOT_NULL = 'null-to-not-null';
    public const ONLY_SAME_SCHEMA_FKS = 'only-same-schema-fks';
    public const PASSWORD = 'password';
    public const PAUSE_FILE = 'pause-file';
    public const PID = 'pid';
    public const PLUGIN = 'plugin';
    public const PORT = 'port';
    public const PRINT = 'print';
    public const PROGRESS = 'progress';
    public const QUIET = 'quiet';
    public const RECURSE = 'recurse';
    public const RECURSION_METHOD = 'recursion-method';
    public const REVERSE_TRIGGERS = 'reverse-triggers';
    public const RESUME = 'resume';
    public const SKIP_CHECK_REPLICA_LAG = 'skip-check-replica-lag';
    public const SKIP_CHECK_SLAVE_LAG = 'skip-check-slave-lag';
    public const SLAVE_USER = 'slave-user';
    public const SLAVE_PASSWORD = 'slave-password';
    public const REPLICA_USER = 'replica-user';
    public const REPLICA_PASSWORD = 'replica-password';
    public const SET_VARS = 'set-vars';
    public const SLEEP = 'sleep';
    public const SOCKET = 'socket';
    public const STATISTICS = 'statistics';
    public const SWAP_TABLES = 'swap-tables';
    public const NO_SWAP_TABLES = 'no-swap-tables';
    public const TRIES = 'tries';
    public const USER = 'user';
    public const VERSION = 'version';
    public const VERSION_CHECK = 'version-check';
    public const NO_VERSION_CHECK = 'no-version-check';
    public const WHERE = 'where';
    public const FAIL_ON_STOPPED_REPLICATION = 'fail-on-stopped-replication';
    public const NO_FAIL_ON_STOPPED_REPLICATION = 'no-fail-on-stopped-replication';

    public static function getAll(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    public static function isValid($option): bool
    {
        $constants = static::getAll();
        return in_array($option, $constants);
    }
}