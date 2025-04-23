DELIMITER //

CREATE TRIGGER before_booking_insert 
BEFORE INSERT ON bookingdata
FOR EACH ROW
BEGIN
    DECLARE new_id VARCHAR(10);
    -- Generate a random 10-digit number
    SET new_id = LPAD(FLOOR(RAND() * 9999999999), 10, '0');
    
    -- Keep generating until we find a unique one
    WHILE EXISTS (SELECT 1 FROM bookingdata WHERE bookingId = new_id) DO
        SET new_id = LPAD(FLOOR(RAND() * 9999999999), 10, '0');
    END WHILE;
    
    -- Set the new bookingId
    SET NEW.bookingId = new_id;
END//

DELIMITER ; 