<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'subsource' => [
        'title'          => 'Promos',
        'title_singular' => 'SubSource',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'Name',
            'name_helper'              => 'Enter name of Admin/Client/Organisation',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'roles'                    => 'Roles',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
            'user_type'                => 'User Type',
            'user_type_helper'         => 'Select the type of user',
            'address'                  => 'Address',
            'address_helper'           => ' ',
            'contact_number_1'         => 'Contact Number 1',
            'contact_number_1_helper'  => ' ',
            'contact_number_2'         => 'Contact Number 2',
            'contact_number_2_helper'  => ' ',
            'website'                  => 'Website',
            'website_helper'           => ' ',
            'client'                   => 'Client',
            'client_helper'            => 'If role is Client then select client to which this user belongs',
            'agency'                   => 'Agency',
            'agency_helper'            => 'If uset type agency select the agency to which this user belongs to',
        ],
    ],
    'project' => [
        'title'          => 'Project',
        'title_singular' => 'Project',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'name'               => 'Name',
            'name_helper'        => 'project name',
            'start_date'         => 'Start Date',
            'start_date_helper'  => ' ',
            'end_date'           => 'End Date',
            'end_date_helper'    => ' ',
            'created_by'         => 'Created By',
            'created_by_helper'  => 'user who created this project',
            'client'             => 'Client',
            'client_helper'      => 'Select client of this project',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
            'location'           => 'Location',
            'location_helper'    => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
            'custom'           => 'Custom Fields',
            'custom_fields'  => ' ',
            'essential'           => 'Essential Fields',
            'essential_fields'  => ' ',
            'sales'           => 'Sales Fields',
            'sales_fields'  => ' ',
            'system'           => 'System Fields',
            'system_fields'  => ' '
        ],
    ],
    'campaign' => [
        'title'          => 'Campaign',
        'title_singular' => 'Campaign',
        'fields'         => [
            'id'                   => 'ID',
            'id_helper'            => ' ',
            'name'        => 'Campaign Name',
            'name_helper' => ' ',
            'start_date'           => 'Start Date',
            'start_date_helper'    => ' ',
            'source'               => 'Source',
            'source_helper'        => ' ',
            'created_at'           => 'Created at',
            'created_at_helper'    => ' ',
            'updated_at'           => 'Updated at',
            'updated_at_helper'    => ' ',
            'deleted_at'           => 'Deleted at',
            'deleted_at_helper'    => ' ',
            'end_date'             => 'End Date',
            'end_date_helper'      => ' ',
            'project'              => 'Project',
            'project_helper'       => 'Which project this campaign belong to',
            'agency'               => 'Agency',
            'agency_helper'        => ' ',
        ],
    ],
    'field' => [
        'title'          => 'Field',
        'title_singular' => 'Field',

    ],
    'lead' => [
        'title'          => 'Leads',
        'title_singular' => 'Lead',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'project'             => 'Project',
            'project_helper'      => 'select project of the lead',
            'campaign'            => 'Campaign',
            'campaign_helper'     => 'Campaign from which lead is generated',
            'lead_details'        => 'Lead Details',
            'lead_details_helper' => 'details of leads in json format',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
            'email'               => 'Email',
            'phone'               => 'Phone No.',
            'ref_num' =>'ref_num'
        ],
    ],
    'auditLog' => [
        'title'          => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'subject_id'          => 'Subject ID',
            'subject_id_helper'   => ' ',
            'subject_type'        => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id'             => 'User ID',
            'user_id_helper'      => ' ',
            'properties'          => 'Properties',
            'properties_helper'   => ' ',
            'host'                => 'Host',
            'host_helper'         => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
        ],
    ],
    'client' => [
        'title'          => 'Client',
        'title_singular' => 'Client',
        'fields'         => [
            'id'                      => 'ID',
            'id_helper'               => ' ',
            'name'                    => 'Name',
            'name_helper'             => 'name',
            'email'                   => 'Email',
            'email_helper'            => ' ',
            'website'                 => 'Website',
            'website_helper'          => ' ',
            'contact_number_1'        => 'Contact No. 1',
            'contact_number_1_helper' => ' ',
            'contact_number_2'        => 'Contact No. 2',
            'contact_number_2_helper' => ' ',
            'other_details'           => 'Other Details',
            'other_details_helper'    => 'provide any other details if required',
            'created_at'              => 'Created at',
            'created_at_helper'       => ' ',
            'updated_at'              => 'Updated at',
            'updated_at_helper'       => ' ',
            'deleted_at'              => 'Deleted at',
            'deleted_at_helper'       => ' ',
        ],
    ],
    'clientManagement' => [
        'title'          => 'Client Management',
        'title_singular' => 'Client Management',
    ],
    'agencyManagement' => [
        'title'          => 'Agency Management',
        'title_singular' => 'Agency Management',
    ],
    'agency' => [
        'title'          => 'Agency',
        'title_singular' => 'Agency',
        'fields'         => [
            'id'                      => 'ID',
            'id_helper'               => ' ',
            'name'                    => 'Name',
            'name_helper'             => 'Agency name',
            'email'                   => 'Email',
            'email_helper'            => ' ',
            'contact_number_1'        => 'Contact No. 1',
            'contact_number_1_helper' => ' ',
            'created_at'              => 'Created at',
            'created_at_helper'       => ' ',
            'updated_at'              => 'Updated at',
            'updated_at_helper'       => ' ',
            'deleted_at'              => 'Deleted at',
            'deleted_at_helper'       => ' ',
        ],
    ],
    'source' => [
        'title'          => 'Source',
        'title_singular' => 'Source',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'project'           => 'Project',
            'project_helper'    => ' ',
            'campaign'          => 'Campaign',
            'campaign_helper'   => ' ',
            'name'              => 'Name',
            'name_helper'       => 'Name of source',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'stages' => [
        'title'          => 'Stages',
        'title_singular' => 'stages',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'name'               => 'Name',
            'name_helper'        => 'project name',
            'start_date'         => 'Start Date',
            'start_date_helper'  => ' ',
            'end_date'           => 'End Date',
            'end_date_helper'    => ' ',
            'created_by'         => 'Created By',
            'created_by_helper'  => 'user who created this project',
            'client'             => 'Client',
            'client_helper'      => 'Select client of this project',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
            'location'           => 'Location',
            'location_helper'    => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
            'custom'           => 'Custom Fields',
            'custom_fields'  => ' ',
            'essential'           => 'Essential Fields',
            'essential_fields'  => ' ',
            'sales'           => 'Sales Fields',
            'sales_fields'  => ' ',
            'system'           => 'System Fields',
            'system_fields'  => ' '
        ],
    ],
];
