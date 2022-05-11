set names utf8mb4;

drop schema if exists date_studies;
create schema date_studies;
use date_studies;

--
-- schedules
--
create table schedules
(
  id         int not null auto_increment primary key,
  event      varchar(191),
  date       datetime,
  created_at timestamp default current_timestamp
) Engine = InnoDB;

insert into schedules(event, date)
-- exemplo de escape com double single quotes
values ('Comemorações D''água de Abril', '2022-04-04 08:00:00'),
       -- exemplo de escape com backslashes
       ('Homenagem \'às\' Mães', '2022-05-05 12:00:00'),
       ('Exposição "Agropecuária"', '2022-06-06 22:00:00');
