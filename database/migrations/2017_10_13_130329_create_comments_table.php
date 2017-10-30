<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * content  内容      字符串     not null    255      varchar
         * user_id  用户id    数字       not null    11        int
         * blog_id  博客id    数字       not null    100       int
         *          */

        /**
         * 设置字段的时候，默认不能为空
         * string  -> varchar
         * comment -> 注释
         * unique  -> 设置字段的唯一性
         */
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content', 255)->comment('评论内容');
            $table->integer('user_id', false, true)->length(11)->comment('用户id');
            $table->integer('blog_id', false, true)->length(100)->comment('博客id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
