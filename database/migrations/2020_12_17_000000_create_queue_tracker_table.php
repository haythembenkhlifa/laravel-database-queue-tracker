<?php

use src\Models\QueueTracker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_tracker', function (Blueprint $table) {

            $table->increments('id');

            $table->string('job_id')->index();
            $table->string('name')->nullable()->index();
            $table->string('queue')->nullable()->index();
            $table->string('status')->default("In progress")->index();


            $table->string('started_at')->nullable()->index();
            $table->string('finished_at')->nullable();

            $table->float('processing_time', 12, 6)->nullable()->index();
            $table->integer('attempt')->default(0);
            $table->integer('tried')->default(1);

            $table->boolean('is_loading')->default(0);

            $table->longText('exception')->nullable();

            $table->string('job_uuid')->nullable()->unique()->index();

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
        Schema::dropIfExists('queue_tracker');
    }
}
