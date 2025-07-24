<?php

namespace App\Services\Interfaces;


interface CourseServiceInterface 
{
    public function createCourse(array $data);
    public function updateCourse(array $data);
}