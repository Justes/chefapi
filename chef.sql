

create table users(
    id int not null auto_increment primary key,
    nickname varchar(32) not null default '',
    openid varchar(32) not null default '',
    created_at datetime default null,
    updated_at datetime default null
);
