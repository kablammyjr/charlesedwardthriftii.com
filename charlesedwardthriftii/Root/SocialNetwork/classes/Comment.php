<?php

class Comment{

    public static function createComment($commentbody, $postId, $userid) {

        if (strlen($commentbody) > 160 || strlen($commentbody) < 1) {
            die('Incorrect length!');
        }

        if (!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postId))) {
            echo 'Invalid post ID!';
        } else {
            DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$commentbody, ':userid'=>$userid, ':postid'=>$postId));
        }
    }

    public static function displayComments($postId) {

        $comments = DB::query('SELECT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$postId));
        
        foreach($comments as $comment) {
            
            echo "<a href='/profile?username=".$comment['username']."'style='color: green'>".$comment['username']."</a> ".$comment['comment']."<hr />";
        }
    }
}

?>