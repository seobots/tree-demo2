SET @name = 'node';
SET @parent = '1';
insert into node (`name`,`parent`,`inheritance`) values (@name, @parent, (select concat(inheritance,'|', sum) from (select inheritance, (select count(id_node)+1 from node as n where n.parent = node.id_node) as sum from node where id_node = @parent) as tmp limit 1));