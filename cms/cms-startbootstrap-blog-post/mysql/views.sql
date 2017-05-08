CREATE VIEW view_posts_with_post_comment_count AS 
SELECT P.post_id, P.post_category_id, P.post_title, P.post_date, P.post_image, P.post_content, P.post_tags, IF (C.comment_post_id IS NULL, 0, COUNT(*)) AS post_comment_count, P.post_status, P.post_user_id, P.views FROM posts AS P LEFT JOIN comments AS C ON P.post_id = C.comment_post_id GROUP BY P.post_id

CREATE VIEW view_posts_with_post_author AS 
SELECT P.post_id, P.post_category_id, P.post_title, CONCAT(U.user_first_name, IF (U.user_last_name IS NOT NULL, " ", ""), U.user_last_name) AS post_author, P.post_date, P.post_image, P.post_content, P.post_tags, P.post_comment_count, P.post_status, P.post_user_id, P.views FROM view_posts_with_post_comment_count AS P LEFT JOIN users AS U ON P.post_user_id = U.user_id