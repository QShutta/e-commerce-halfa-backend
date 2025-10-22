--عملت الملف دة عشان احفظ فيهو كل الاستعلامات 
--المهمة العملتها وعشان اوضح لي روحي لية عملنا الاستعلام
--دة

--عملنا الإستعلام دة لانو في الوضع الطبيعي جدول ال 
--products 
-- براهو ما بحتوي لي علي إسم ال 
--catogery
--عشان كدة انشانا ال 
--view
--دي عشان يكون عندنا جدول يحتوي علي المنتجات وفي نفس الوقت اسماء الاقسام البتنتمي ليها المنتجات
CREATE OR REPLACE VIEW products_view AS
SELECT products.*, catogeries.catogeries_name_ar, catogeries.catogeries_name_en
FROM products
INNER JOIN catogeries ON products.product_catogery = catogeries.catogeries_id;







-- الهدف من هذا الاستعلام هو عرض كل المنتجات
-- مع تحديد إذا كانت مضافة إلى المفضلة بواسطة مستخدم معين 
--(مثلاً user_id = 81)
-- الجزء الأول: المنتجات المضافة إلى المفضلة
--1 as fav?دة بمثل ليك المنتجات المضافة للمفضلة يعني عمود اضافي كدة بنحدد بيهو انو المنتج دة مضاف للمضلة 
SELECT products1_view.*, 1 AS fav 
FROM products1_view 
INNER JOIN favorite 
  ON favorite.product_id = products1_view.products_id 
  AND favorite.user_id = 81
  --Union  :بنستخدمها عشان ندمج النايج بتاعت الاستعلامين سوا في جدول واحد
  --Why did you use union instead of union all?عشان ما عاوز البيانات تتكرر
UNION 
-- الجزء الثاني: المنتجات الغير مضافة إلى المفضلة من نفس المستخدم
--0 as fav?دة بمثل ليك المنتجات الغير مضافة للمفضلة يعني عمود اضافي كدة بنحدد بيهو انو المنتج دة غير مضاف للمضلة
SELECT products1_view.*, 0 AS fav 
FROM products1_view 
-- استخدمنا 
--NOT IN
-- بدلًا من != لأن الاستعلام الداخلي يرجع أكثر من صف 
--(يعني أكثر من products_id)
-- ولو استخدمنا != مع 
--Subquery
-- بيرجع أكثر من صف، حيحصل خطأ:
-- Subquery returns more than 1 row
-- أما 
--NOT IN 
--فهو مخصص لمقارنة قيمة واحدة مع مجموعة من القيم، وده المطلوب هنا
WHERE products_id NOT IN (
    -- هذا الاستعلام الداخلي يرجع المنتجات المضافة إلى المفضلة
    -- ويُستخدم لتصفية المنتجات التي لا تظهر فيه (أي غير مضافة للمفضلة)
    SELECT products1_view.products_id 
    FROM products1_view 
    INNER JOIN favorite 
      ON favorite.product_id = products1_view.products_id 
      AND favorite.user_id = 81
);
-- النتيجة النهائية: جميع المنتجات مع عمود إضافي "fav"
-- إذا كانت القيمة 1 => المنتج مضاف للمفضلة
-- إذا كانت القيمة 0 => المنتج غير مضاف للمفضلة





-- هذا الاستعلام يقوم بجلب جميع المنتجات المفضلة للمستخدمين
-- ويعرض تفاصيل المنتج والمستخدم الذي أضافه إلى المفضلة.

SELECT
    favorite.*,   -- اختر جميع الأعمدة من جدول 'favorite' (المفضلة).
                  -- هذا يشمل عادةً: معرف المفضلة، معرف المستخدم، معرف المنتج، وتاريخ الإضافة.
    products.*,   -- اختر جميع الأعمدة من جدول 'products' (المنتجات).
                  -- هذا يوفر تفاصيل المنتج مثل: الاسم، السعر، الوصف، والصورة.
    users.user_id -- اختر عمود 'user_id' فقط من جدول 'users' (المستخدمين).
                  -- يحدد هذا العمود المستخدم الذي أضاف المنتج إلى المفضلة.
FROM
    favorite      -- ابدأ الاستعلام من جدول 'favorite' كجدول أساسي.
                  -- هذا الجدول يحتوي على سجلات المنتجات التي تم تفضيلها.
INNER JOIN
    users         -- اربط جدول 'favorite' بجدول 'users'.
                  -- الـ INNER JOIN يضمن عرض الصفوف التي لها تطابق في كلا الجدولين فقط.
ON
    users.user_id = favorite.user_id  -- شرط الربط: تطابق معرف المستخدم بين الجدولين.
                                      -- هذا يربط كل منتج مفضل بالمستخدم الصحيح الذي فضله.
INNER JOIN
    products      -- اربط النتيجة الحالية بجدول 'products'.
                  -- هذا الربط يضيف تفاصيل المنتج لكل سجل مفضل.
ON
    products.products_id = favorite.product_id; -- شرط الربط: تطابق معرف المنتج بين الجدولين.
                                                -- هذا يضمن ربط المنتج المفضل بتفاصيله الكاملة.














-- Create or replace a view named 'cart_view'.
-- A view is a virtual table based on the result-set of an SQL statement.
-- This view will simplify querying complex joins later.
CREATE OR REPLACE VIEW cart_view AS
-- Select the columns we want to display in our view.
SELECT 
    -- What is the reson that make you display in the view the orignal price of the product
    -- and the product price after discount both of them in the same table is it not one of them is 
    -- enogh?No in my ui i want to dispaly the orignal price and the price with discount.عشان اوري 
    -- To let the user know that there is a discount on this price and what is the orignal price.
    -- Calculate the total price for each product group.

    
    -- We use SUM() to add up prices and give the column an alias 'productPrice'.
    -- why did we use Sum()? the user may add the same product more than one time to the cart
    -- so we have to calculate the total price for this product in the cart.
    -- For example if the user add product A with price 100$ two times to the cart
    -- the total price for this product in the cart is 200$.
    SUM(products.product_price) AS productPrice,
    
    -- Count the number of products in each group.
    -- We use COUNT() to get the count and alias it 'productCount'.
    -- Why did we use Count()? the user may add the same product more than one time to the cart
    -- so we have to count how many times this product is added to the cart.
    COUNT(cart.cart_product_id) AS productCount,
    
    --دة عشان نتحصل علي سعر المنتج  بعد الخصم 
    SUM(products.product_price-(products.product_price*products.product_discount/100)) AS productPriceAfterDiscopunt,
    -- Select all columns from the 'cart' table.
    cart.*,
    
    -- Select all columns from the 'products' table.
    products.* -- Specify the tables we are pulling data from.
FROM 
    cart
    
-- Join the 'cart' and 'products' tables.
-- An INNER JOIN returns only the rows that have matching values in both tables.
-- The join condition is where a product's ID in the cart matches its ID in the products table.
INNER JOIN products ON cart.cart_product_id = products.products_id
    where cart_order=0  -- Filter the results to include only items that have not been ordered yet (cart_order = 0).


-- جملة GROUP BY
-- ضرورية جداً هنا لكي يتم تطبيق دوال التجميع (SUM و COUNT) بشكل صحيح.

-- ما هي وظيفة GROUP BY هنا بالتحديد؟
-- ببساطة، تقوم بتجميع الصفوف المتطابقة في مجموعات قبل إجراء عملية الحساب.
-- هذا التجميع يتم بناءً على عمودين محددين (نقاط الفصل):

-- 1. cart.cart_user_id (هوية المستخدم):
--    الجدول يحتوي على سلال جميع المستخدمين (طالما cart_order=0).
--    لذلك، يجب أن نفصل الحسابات: لا نريد خلط منتجات المستخدم 'س' مع منتجات المستخدم 'ص'.
--    هذا يضمن أن التجميع يتم داخل سلة كل مستخدم على حدة.

-- 2. cart.cart_product_id (هوية المنتج):
--    داخل سلة المستخدم الواحد، قد يكون المنتج مضافاً أكثر من مرة.
--    هذا التجميع يضمن أن يتم حساب 
--    (SUM) السعر و (COUNT) الكمية للمنتج (أ) لوحده،
--    ويتم حساب (SUM) السعر و (COUNT) الكمية للمنتج (ب) لوحده.
--    (إنه يفصل بين الأصناف داخل السلة الواحدة).

-- الخلاصة: GROUP BY بهذا الشكل تعطينا ملخصاً دقيقاً:
-- سطر واحد لكل [منتج] داخل [سلة مستخدم] مع إجمالي الكمية والسعر لهذا الصنف.
GROUP BY
    cart.cart_product_id,
    cart.cart_user_id;





-- the course instructor create tow views:
-- السبب إنو ما جمعهم من البداية بسيط لكن مهم جدًا في شغل الـ 
-- Database، وخليني أشرح ليك خطوة بخطوة
--
-- 🔹 1. لو جمعهم من البداية
-- يعني لو كتب كود واحد يربط:
-- orders + address + cart + products
-- في نفس الـ VIEW من البداية،
-- النتيجة حتكون كدا:
-- كود ضخم جدًا، طويل، وصعب تفهمه أو تعدله لاحقًا.
--
-- 🔸 أي تعديل بسيط (مثلاً تعديل في العنوان أو الخصم)
-- حيخليه يغير الكود كله من البداية، وده تعب ومخاطرة.
--
-- 🔹 2. لكن لما قسمهم على مرحلتين
-- هو عملها بطريقة ذكية ومنظمة:
--   - order_view: تربط الطلب والعنوان بس
--   - order_details_view: تستخدم الـ order_view الجاهزة، وتضيف ليها المنتجات والسلة



    -- This statement is to create the order_view?
    -- Why did we create this view?
    -- In the order details page in the frontend. we need to display the orderdetails.including the address of the order.
    -- So because of that we create this view that include the order details and the address details both of them in the same
    -- table
    CREATE OR REPLACE VIEW orders_view AS
SELECT orders.*,address.* FROM orders
-- Why did you use the "left join" . I use it because of i want to dispaly all of the orders even if the user will
-- pick up the order_from the store in that case will be no address.and will show all of the orders in the address 
-- section will show null.and this is the condtion of the connection between the 2 tables:
--دة الشرط بتاع الربط بين الجدولين  .حيث انو بفتش في جدول الطلبات .ويطابق اذا في صف مطابق ليهو مع جدول العناوين.اذا 
--في صف مطابق ليهو طوالي حيلصقهم سوا اذا لا .حيخلي فاضي بي نل .
LEFT JOIN address ON orders.order_address=address.address_id



















-- Create or replace a view named 'cart_view'.
-- A view is a virtual table based on the result-set of an SQL statement.
-- This view will simplify querying complex joins later.
CREATE OR REPLACE VIEW order_details_view AS
-- Select the columns we want to display in our view.
SELECT 
    -- What is the reson that make you display in the view the orignal price of the product
    -- and the product price after discount both of them in the same table is it not one of them is 
    -- enogh?No in my ui i want to dispaly the orignal price and the price with discount.عشان اوري 
    -- To let the user know that there is a discount on this price and what is the orignal price.
    -- Calculate the total price for each product group.

    
    -- We use SUM() to add up prices and give the column an alias 'productPrice'.
    -- why did we use Sum()? the user may add the same product more than one time to the cart
    -- so we have to calculate the total price for this product in the cart.
    -- For example if the user add product A with price 100$ two times to the cart
    -- the total price for this product in the cart is 200$.
    SUM(products.product_price) AS productPrice,
    
    -- Count the number of products in each group.
    -- We use COUNT() to get the count and alias it 'productCount'.
    -- Why did we use Count()? the user may add the same product more than one time to the cart
    -- so we have to count how many times this product is added to the cart.
    COUNT(cart.cart_product_id) AS productCount,
    
    --دة عشان نتحصل علي سعر المنتج  بعد الخصم 
    SUM(products.product_price-(products.product_price*products.product_discount/100)) AS productPriceAfterDiscopunt,
    -- Select all columns from the 'cart' table.
    cart.*,
    
    -- Select all columns from the 'products' table.
    products.* -- Specify the tables we are pulling data from.
FROM 
    cart
    
-- Join the 'cart' and 'products' tables.
-- An INNER JOIN returns only the rows that have matching values in both tables.
-- The join condition is where a product's ID in the cart matches its ID in the products table.
INNER JOIN products ON cart.cart_product_id = products.products_id
-- شرط الربط دة  بعمل فحص انو السلة هي تابع للطلب او الاورد المعين يعني في في  طلب 
-- id have an id and that id is exist in the cart table that means the cart is belong to that order.
INNER JOIN orders_view ON orders_view.order_id = cart.cart_order 
    -- Why did we say cart_order!=0 that means انو المستخدم اكمل الطلب بالفعل يعني 
    -- when the cart_order=0 that measn the user did not complete the order yet and is هو لسة في السلة بطلب في المنتج .
    where cart_order!=0 


-- جملة GROUP BY
-- ضرورية جداً هنا لكي يتم تطبيق دوال التجميع (SUM و COUNT) بشكل صحيح.

-- ما هي وظيفة GROUP BY هنا بالتحديد؟
-- ببساطة، تقوم بتجميع الصفوف المتطابقة في مجموعات قبل إجراء عملية الحساب.
-- هذا التجميع يتم بناءً على عمودين محددين (نقاط الفصل):

-- 1. cart.cart_user_id (هوية المستخدم):
--    الجدول يحتوي على سلال جميع المستخدمين (طالما cart_order=0).
--    لذلك، يجب أن نفصل الحسابات: لا نريد خلط منتجات المستخدم 'س' مع منتجات المستخدم 'ص'.
--    هذا يضمن أن التجميع يتم داخل سلة كل مستخدم على حدة.

-- 2. cart.cart_product_id (هوية المنتج):
--    داخل سلة المستخدم الواحد، قد يكون المنتج مضافاً أكثر من مرة.
--    هذا التجميع يضمن أن يتم حساب 
--    (SUM) السعر و (COUNT) الكمية للمنتج (أ) لوحده،
--    ويتم حساب (SUM) السعر و (COUNT) الكمية للمنتج (ب) لوحده.
--    (إنه يفصل بين الأصناف داخل السلة الواحدة).

-- الخلاصة: GROUP BY بهذا الشكل تعطينا ملخصاً دقيقاً:
-- سطر واحد لكل [منتج] داخل [سلة مستخدم] مع إجمالي الكمية والسعر لهذا الصنف.


-- ده معناهو إننا بنقسم النتائج إلى مجموعات (Groups)
-- كل مجموعة بتتكوّن من:

-- نفس المنتج (cart_product_id)

-- نفس المستخدم (cart_user_id)

-- نفس الطلب (cart_order)
-- the user can add more than one order so we hav eto make sure that the order is blelong to the same user,and the product is 
-- belong to the same card.
GROUP BY
    cart.cart_product_id,
    cart.cart_user_id;
-- THE BENFIT OF THE cart.cart_order in the groub by is :
-- "أجمع لي كل الصفوف اللي عندها نفس 
-- cart_product_id ونفس cart_user_id في صف واحد."
-- مثال للشرح :
-- الحاصل قبل ما تضيف cart.cart_order:
-- SQL بيشوف مثلاً:
-- cart_product_id | cart_user_id | cart_order
-- 85              | 81           | 1
-- 85              | 81           | 2
-- 85              | 81           | 3
--
-- كل ديل عندهم نفس المنتج 
-- (cart_product_id = 85) ونفس المستخدم (cart_user_id = 81)
-- فـ SQL بيجمعهم في صف واحد فقط لأنك ما قلت ليه يفرق بينهم حسب 
-- cart_order.
-- فبيجمع الأسعار والعدادات في صف واحد كبير.

-- بعد ما تضيف cart.cart_order في الـ GROUP BY:
-- GROUP BY cart.cart_product_id, cart.cart_user_id, cart.cart_order
--
-- كأنك بتقول ليه:
-- "لو اختلف رقم الطلب (cart_order)، خليه صف مختلف."
--
-- يعني الحالات التلاتة فوق حتكون 3 صفوف مختلفة بدل صف واحد، لأن cart_order مختلف.

    cart.cart_order

