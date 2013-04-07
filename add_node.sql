SET @name = 'node';
SET @parent = '1';
insert into node (`name`,`parent`,`inheritance`) values (@name, @parent, (select concat(inheritance,'|', sum) from (select inheritance, (select (`id_node` +1) from node as n ORDER BY `n`.`id_node` DESC LIMIT 1) as sum from node where id_node = @parent) as tmp limit 1));