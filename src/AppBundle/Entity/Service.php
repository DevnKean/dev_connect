<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service
{
    const SERVICE_OUTSOURCING = 'Outsourcing';
    const SERVICE_VIRTUAL_ASSISTANT = 'Virtual Assistant';
    const SERVICE_RECEPTION_SERVICES = 'Reception Services';
    const SERVICE_CONSULTANTS = 'Consultants';
    const SERVICE_TECHNOLOGY = 'Technology';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @var Profile[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Profile", mappedBy="service", cascade={"persist", "remove"})
     * @ORM\OrderBy({"order" = "ASC"})
     */
    private $profiles;

    /**
     * @var Contract[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContractService", mappedBy="service")
     */
    private $allocatedContracts;

    /**
     * @var Lead
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Lead", mappedBy="service")
     */
    private $leads;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Profile[] | ArrayCollection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param Profile[] $profiles
     */
    public function setProfiles($profiles)
    {
        $this->profiles = $profiles;
    }

    /**
     * @return Contract[]
     */
    public function getAllocatedContracts()
    {
        return $this->allocatedContracts;
    }

    /**
     * @param Contract[] $allocatedContracts
     */
    public function setAllocatedContracts($allocatedContracts)
    {
        $this->allocatedContracts = $allocatedContracts;
    }

    public function __toString()
    {
        return ucfirst($this->name);
    }

    public static function getServices()
    {
        return [
            'Outsourcing',
            'Consultants',
            'Virtual Assistant'
        ];
    }

    /**
     * @param Profile $profile
     */
    public function addProfile($profile)
    {
        $profile->setService($this);
        $this->profiles[] = $profile;
    }

    public static function getFunctions()
    {
        return [
            'Customer Service - Inbound only',
            'Customer Service - Outbound only',
            'Customer Service - blended',
            'Sales - Inbound',
            'Sales - Blended',
            'Sales - Outbound (cold calls)',
            'Sales - Outbound (warm calls)',
            'Sales - Outbound (hot calls)',
            'Technical Support',
            'Collections',
            'Surveys',
            'Emergency/Roadside support',
            'Live Chat',
            'Video Chat',
            'Fundraising',
            'Lead Generation',
            'Appointment Setting'
        ];
    }
    public static function getlengthOFtimes_S()
    {
        return [
            'Still a current customer',
            'Under a week',
            'Under a month',
            '1 to 3 months',
            '4 to 6 months',
            '7 to 12 months',
            '1 to 2 years',
            '3 to 5 years',
            'More than 5 years',
        ];
    }

    public static function getVAFunctions()
    {
        return [
            'Lead Generation',
            'Telemarketing (outbound calls)',
            'Surveys (outbound calls)',
            'Reception Services (inbound)',
            'Sales(inbound lead conversion)',
            'Sales (inbound order taking) ',
            'Message taking (inbound) ',
            'Product enquiries (inbound)',
            'Surveys (inbound)',
            'Appointments/bookings(inbound)',
            'Social Media management',
            'Administrative Support',
            'Data entry',
            'Calendar management',
            'Research',
            'Reporting',
            'Book keeping',
            'Scheduling' 
        ];
    }

    public static function getConsulFunctions()
    {
        return [            
            'Operational Reviews/Health Checks',
            'Workforce Management/Optimisation',
            'Employee Engagement',
            'Leadership and Executive Coaching & Mentoring',
            'Customer Experience / Voice of the Customer',
            'Customer Journey Mapping',
            'Process Optimisation & Mapping',
            'Technology Design & Implementation',
            'Self-Service strategy',
            'Cost Optimisation/reduction',
            'CX Strategy',
            'Social Media',
            'Procurement including RFI, RFP, RFQs',
            'Key Performance Indicators',
            'Quality Frameworks',
            'Knowledge Management',
            'Sales Optimisation',
            'BCP/Disaster Recovery',
            'Project Management',
            'Change Management',
            'Telephony design (ACD/PBX/IVR)',
            'Artificial Intelligence/Robotic Process Automation',
            'Workforce Transformation',
            'Other*',
        ];
    }

    /**
     * @return Lead
     */
    public function getLeads()
    {
        return $this->leads;
    }

    /**
     * @param Lead $leads
     */
    public function setLeads($leads)
    {
        $this->leads = $leads;
    }

}

