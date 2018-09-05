DELIMITER $$
CREATE PROCEDURE `sp_orders_save`(
pidorder INT,
pidcart int(11),
piduser int(11),
pidstatus int(11),
pidaddress int(11),
pvltotal decimal(10,2)
)
BEGIN
	
	IF pidorder > 0 THEN
		
		UPDATE pedido
        SET
			idcart = pidcart,
            iduser = piduser,
            idstatus = pidstatus,
            idaddress = pidaddress,
            vltotal = pvltotal
		WHERE idorder = pidorder;
        
    ELSE
    
		INSERT INTO pedido (idcart, iduser, idstatus, idaddress, vltotal)
        VALUES (pidcart, piduser, pidstatus, pidaddress, pvltotal);
		
		SET pidorder = LAST_INSERT_ID();
        
    END IF;
    
    SELECT * 
    FROM pedido a
    INNER JOIN status_pedido b USING(idstatus)
    INNER JOIN carrinho c USING(idcart)
    INNER JOIN user d ON d.iduser = a.iduser
    INNER JOIN tb_addresses e USING(idaddress)
    WHERE idorder = pidorder;
    
END$$
DELIMITER ;
