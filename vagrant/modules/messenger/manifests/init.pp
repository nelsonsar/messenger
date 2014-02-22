class messenger {
    $packages = [
        "make",
        "php-pear",
        "php5",
        "php5-curl",
        "php5-dev",
        "php5-sqlite",
        "sqlite3",
    ]

    package { $packages:
        ensure => present
    }

    apache::vhost { 'messenger.dev':
        docroot => '/var/www/messenger/web',
        domain => 'messenger.dev',
        vhost_name => 'messenger',
        port => 8080
    }

    apache::module { "mod_rewrite":
        module_name => "rewrite"
    }

    exec { "sqlite-database-creation":
        unless => "test -f /var/www/messenger/resources/messenger.db",
        command => "cat /vagrant/etc/db.sql | sqlite3 /var/www/messenger/resources/messenger.db"
    }

    exec { "sqlite-test-database-creation":
        unless => "test -f /var/www/messenger/resources/messenger_test.db",
        command => "cat /vagrant/etc/db.sql | sqlite3 /var/www/messenger/resources/messenger_test.db"
    }
}
