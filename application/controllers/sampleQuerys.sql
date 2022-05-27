SELECT MAX(price) max_price, AVG(price) avg_price, SUM(price) total_price FROM products WHERE id = 3;

INSERT INTO `stock`.`store_record` (`id`, `pid`, `sid`, `qty`, `date`) VALUES (NULL, '3', '3', '77', CURRENT_TIMESTAMP), (NULL, '4', '3', '94', CURRENT_TIMESTAMP);

SELECT products.id as id, products.price*orders_item.qty as total FROM products JOIN orders_item ON products.id = orders_item.product_id;

SELECT category.name, 
		product.name, 
		product.attribute, 
		product.price, 
		store_record.qty, 
		store.name
FROM products 
JOIN category
	 ON products.category_id = category.id
JOIN store_record
	 ON products.id = store_record.pid
JOIN store
	 products.store_id = store.id 

-- PRODUCTS REPORT --

SELECT  products.category_id,
		products.name, 
		products.attribute_value_id, 
		products.price, 
		store_record.qty, 
		stores.name
FROM products 
INNER JOIN store_record
	 ON products.id = store_record.pid
INNER JOIN stores
	 ON products.store_id = stores.id;


-- SALES REPORT --

SELECT products.name,
		products.category_id,
		orders_item.qty,
		products.price,
		orders.vat_charge,
		orders.service_charge,
		orders.discount,
		products.price*orders_item.qty as total_price,
		orders.paid_status,
		orders.date_time,
		orders_item.store_id,
		users.username
FROM orders
JOIN orders_item
	ON orders.id = orders_item.order_id
JOIN products
	ON orders_item.product_id = products.id
JOIN stores
	ON orders_item.store_id = stores.id
JOIN users
	ON orders.user_id = users.id;


-- USERS SALES REPORT --

SELECT
orders.user_id, 
users.username,
SUM(
	CASE WHEN orders.with_vat = 1 THEN 1 ELSE 0 END
) WITHVAT,
SUM(
	CASE WHEN orders.with_vat = 0 THEN 1 ELSE 0 END
) WITHOUTVAT
FROM orders 
JOIN users 
ON orders.user_id = users.id 
	GROUP BY ORDERS.USER_ID,
	USERS.USERNAME;




SELECT users.username,
		count(v_user_vat_sales.uid) AS vat_sales,
		count(v_user_non_vat.uid) AS nonvat_sales,
		count(v_user_vat_sales.uid)+count(v_user_non_vat.uid) AS totoal_sales
FROM users
JOIN v_user_vat_sales
	ON users.id = v_user_vat_sales.uid
JOIN v_user_non_vat
	ON users.id = v_user_non_vat.uid;

SELECT orders.user_id, 
		users.username,
		orders.with_vat,
		count(orders.with_vat)-- = 1) as total_vat,
--		count(orders.with_vat = 0) as total_nonvat 
FROM orders 
JOIN users 
	ON orders.user_id = users.id 
	GROUP BY ORDERS.USER_ID,
	USERS.USERNAME,
	orders.with_vat;	



-- Customer activity

-- purchase
-- total non - vat purchase
-- tot vat purchase
-- credit purchase
-- cash purchase
-- payment stat\\0

-- SELECT
-- orders.user_id, 
-- users.username,
-- SUM(
-- 	CASE WHEN orders.with_vat = 1 THEN 1 ELSE 0 END
-- ) WITHVAT,
-- SUM(
-- 	CASE WHEN orders.with_vat = 0 THEN 1 ELSE 0 END
-- ) WITHOUTVAT
-- FROM orders 
-- JOIN users 
-- ON orders.user_id = users.id 
-- 	GROUP BY ORDERS.USER_ID,
-- 	USERS.USERNAME,
-- 	orders.with_vat;	




SELECT
customers.fullname, 
customers.tin,
orders_item.product_id,
SUM(
	CASE WHEN orders.with_vat = 1 THEN 1 ELSE 0 END
) withvat,
SUM(
	CASE WHEN orders.with_vat = 0 THEN 1 ELSE 0 END
) withoutvat,
SUM(
	CASE WHEN orders.paid_status = 2 THEN 1 ELSE 0 END
) credit
SUM(
	CASE WHEN orders.paid_status = 1 THEN 1 ELSE 0 END
) cash
FROM orders 
JOIN customers
ON orders.customer_id = customers.id
JOIN orders_item
	ON orders.id = orders_item.order_id
	GROUP BY ORDERS.USER_ID;


SELECT
customers.fullname, 
customers.tin,
orders.id,
orders_item.product_id
FROM orders
JOIN customers
	ON orders.customer_id = customers.id
JOIN orders_item
	ON orders.id = orders_item.order_id;


SELECT
orders.user_id, 
users.username,
SUM(
	CASE WHEN orders.with_vat = 1 THEN 1 ELSE 0 END
) WITHVAT,
SUM(
	CASE WHEN orders.with_vat = 0 THEN 1 ELSE 0 END
) WITHOUTVAT
FROM orders 
JOIN users 
ON orders.user_id = users.id 
	GROUP BY ORDERS.USER_ID,
	USERS.USERNAME;

SELECT
customers.id,
customers.fullname, 
count(
	case when orders.with_vat = 1 THEN 1 else 0 end
) as with_vat,
count(
	case when orders.with_vat = 0 THEN 1 else 0 end
) as with_out_vat,
count(
	case when orders.paid_status = 2 THEN 1 else 0 end
) as cash_purchase,
count(
	case when orders.paid_status = 1 THEN 1 else 0 end
) as credit_purchase,
count(*)
from customers
inner join orders on customers.id = orders.customer_id
group by customers.id


