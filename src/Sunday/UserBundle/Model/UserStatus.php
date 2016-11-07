<?php
namespace Sunday\UserBundle\Model;

/**
 * There are Five type User Status until now.
 *
 * Type one: Healthy
 * The user who is healthy MUST keep his/her 60 percent of all health value at least.
 *
 * Type two: weak
 * If the health value of somebody is less than 60 percent and more than 10 percent of his/her max value,
 * he or she is defined as weak.
 *
 * Type three: possessed
 * If somebody is possessed by witchcraft from someone, the status is set this.
 *
 * Type four: terminal
 * If the health value of somebody is less than 10 percent of his/her max value, he or she was in danger
 * of losing his/her health value of life. So warning him/her, show him/her some helpful advice by Igor.
 *
 * Type Five: dead
 * As the word 'dead', the user is dead, but that just means the health value of the user is down to 0.
 * Most ability of the user should be limited until he/she has been saved by other user who has
 * the ability of remedy.
 *
 * Todo: Make it dynamic in background, users could customize by self.
 *
 * Class UserStatus
 * @package Sunday\UserBundle\Model
 */
class UserStatus
{
    const USER_STATUS_HEALTHY   = "healthy";
    const USER_STATUS_WEAK      = "weak";
    const USER_STATUS_POSSESSED = "possessed";
    const USER_STATUS_TERMINAL  = "terminal";
    const USER_STATUS_DEATH     = "dead";
}