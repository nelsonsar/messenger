class php-rabbitmq {
    exec { "download_librabbitmq-c_tarball":
        command => "wget https://github.com/alanxz/rabbitmq-c/releases/download/v0.4.1/rabbitmq-c-0.4.1.tar.gz",
        cwd => "/tmp",
        unless => "whereis librabbitmq | grep librabbitmq.so",
        notify => Exec["unpack_librabbitmq-c_tarball"]
    }

    exec { "unpack_librabbitmq-c_tarball":
        command => "tar -zxf rabbitmq-c-0.4.1.tar.gz",
        cwd => "/tmp",
        refreshonly => true,
        require => Exec["download_librabbitmq-c_tarball"],
        notify => Exec["remove_librabbitmq_tarball"]
    }

    exec { "remove_librabbitmq_tarball":
        command => "rm -f rabbitmq-c-0.4.1.tar.gz",
        cwd => "/tmp",
        refreshonly => true,
        require => Exec["unpack_librabbitmq-c_tarball"],
        notify => Exec["install_librabbitmq-c"];
    }

    exec { "install_librabbitmq-c":
        command => "sudo sh configure && make && sudo make install",
        cwd => "/tmp/rabbitmq-c-0.4.1",
        refreshonly => true,
        require => Exec["remove_librabbitmq_tarball"],
    }

    exec { "install_amqp_pecl_extension":
        command => "sudo pecl install amqp",
        require => Exec["install_librabbitmq-c"],
        unless => "pecl list | grep amqp",
        notify => File["/etc/php5/conf.d/amqp.ini"]
    }

    file { "/etc/php5/conf.d/amqp.ini":
        ensure => present,
        source => "/vagrant/etc/php5/conf.d/amqp.ini",
        require => Exec["install_amqp_pecl_extension"]
    }
}
