USE `db_ecommerce`;
DROP procedure IF EXISTS `sp_products_save`;

DELIMITER $$
USE `db_ecommerce`$$
CREATE PROCEDURE `sp_products_save`(
pidproduct int(11),
pdesproduct varchar(64),
pvlprice decimal(10,2),
pvlwidth decimal(10,2),
pvlheight decimal(10,2),
pvllength decimal(10,2),
pvlweight decimal(10,2),
pdesurl varchar(128)
)
BEGIN
	
	IF pidproduct > 0 THEN
		
		UPDATE produto
        SET 
			descricao = pdesproduct,
            preco = pvlprice,
            comprimento = pvlwidth,
            altura = pvlheight,
            largura = pvllength,
            peso = pvlweight,
            url = pdesurl
        WHERE idproduto = pidproduct;
        
    ELSE
		
		INSERT INTO produto (descricao, preco, comprimento, altura, largura, peso, url) 
        VALUES(pdesproduct, pvlprice, pvlwidth, pvlheight, pvllength, pvlweight, pdesurl);
        
        SET pidproduct = LAST_INSERT_ID();
        
    END IF;
    
    SELECT * FROM produto WHERE idproduto = pidproduct;
    
END$$

DELIMITER ;

