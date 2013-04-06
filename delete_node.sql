SET @id = '1';
delete from `node` where `inheritance` like concat( (SELECT `inheritance` FROM (SELECT `inheritance` FROM `node` WHERE `id_node` = @id) as `tmp`), '%' );