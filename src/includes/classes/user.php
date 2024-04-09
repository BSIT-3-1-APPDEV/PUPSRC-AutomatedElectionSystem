<?php

/**
 * User function utilities.
 *
 */
class User
{
    private $id;
    private $last_name;
    private $first_name;
    private $middle_name;
    private $suffix;
    private $year_level;
    private $section;
    private $email;
    private $status;
    private $vote_status;
    private $type;
    private $organization;

    public function __construct($id, $type, $organization, $last_name, $first_name, $middle_name, $suffix, $year_level, $section, $email, $status, $vote_status)
    {
        $this->id = $id;
        $this->last_name = $last_name;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->suffix = $suffix;
        $this->year_level = $year_level;
        $this->section = $section;
        $this->email = $email;
        $this->status = $status;
        $this->vote_status = $vote_status;
        $this->type = $type;
        $this->organization = $organization;
    }

    // Getter methods
    public function getUserId()
    {
        return $this->id;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

    public function getYearLevel()
    {
        return $this->year_level;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getVoteStatus()
    {
        return $this->vote_status;
    }

    public function getUserType()
    {
        return $this->type;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    // Setter methods
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
    }

    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    public function setYearLevel($year_level)
    {
        $this->year_level = $year_level;
    }

    public function setSection($section)
    {
        $this->section = $section;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setVoteStatus($vote_status)
    {
        $this->vote_status = $vote_status;
    }

    public function setUserType($type)
    {
        $this->type = $type;
    }

    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }
}
