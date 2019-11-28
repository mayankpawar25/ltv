<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	private $permissions;

    public $timestamps = false;



    function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }


    function gen_checkbox($name)
    {
    	$checked 		= "";
    	$permissions 	= $this->get_permissions();

    	if($permissions)
    	{
    		$checked 		= (isset($permissions[$name]) && ($permissions[$name]['value'] == 1) ) ? 'checked' : '';
    	}
    	
        return "<input $checked  type='checkbox' name='permissions[".$name."]' value='1'>";
    }

    static function dropdowns($permissions = NULL)
    {
    	$myClass = new Role();
    	$myClass->set_permissions($permissions);

    	$data['permissions_checkboxes'] = [

                [
                    'name'          => 'General Setting', 
                    'view'          => $myClass->gen_checkbox('gsettings'.'_view'), 
                    'view_own'      => '', 
                    'create'        => '', 
                    'edit'          => $myClass->gen_checkbox('gsettings'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('gsettings'.'_delete'), 
                ],
                [
                    'name'          => 'Email Setting', 
                    'view'          => $myClass->gen_checkbox('email_settings'.'_view'), 
                    'view_own'      => '', 
                    'create'        => '', 
                    'edit'          => $myClass->gen_checkbox('email_settings'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('email_settings'.'_delete'), 
                ],
                [
                    'name'          => 'SMS Setting', 
                    'view'          => $myClass->gen_checkbox('sms_settings'.'_view'), 
                    'view_own'      => '', 
                    'create'        => '', 
                    'edit'          => $myClass->gen_checkbox('sms_settings'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('sms_settings'.'_delete'), 
                ],
                [
                    'name'          => 'CRM Setting', 
                    'view'          => $myClass->gen_checkbox('crm_settings'.'_view'), 
                    'view_own'      => '', 
                    'create'        => '', 
                    'edit'          => $myClass->gen_checkbox('crm_settings'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('crm_settings'.'_delete'), 
                ],
                [
                    'name'          => 'User Groups', 
                    'view'          => $myClass->gen_checkbox('user_groups'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('user_groups'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('user_groups'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('user_groups'.'_delete'), 
                ],
                [
                    'name'          => 'Coupon Setting', 
                    'view'          => $myClass->gen_checkbox('coupons'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('coupons'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('coupons'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('coupons'.'_delete'), 
                ],
                [
                    'name'          => 'Category Management', 
                    'view'          => $myClass->gen_checkbox('categorys'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('categorys'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('categorys'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('categorys'.'_delete'), 
                ],
                [
                    'name'          => 'Product Attributes', 
                    'view'          => $myClass->gen_checkbox('product_attr'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('product_attr'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('product_attr'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('product_attr'.'_delete'), 
                ],
                [
                    'name'          => 'Product Management', 
                    'view'          => $myClass->gen_checkbox('products'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('products'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('products'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('products'.'_delete'), 
                ],
                [
                    'name'          => 'Shopkeeper Management', 
                    'view'          => $myClass->gen_checkbox('shopkeepers'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('shopkeepers'.'_view_own'),
                    'create'        => $myClass->gen_checkbox('shopkeepers'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('shopkeepers'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('shopkeepers'.'_delete'), 
                ],
                [
                    'name'          => 'Job Card Management', 
                    'view'          => $myClass->gen_checkbox('job_cards'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('job_cards'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('job_cards'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('job_cards'.'_delete'), 
                ],
                [
                    'name'          => 'Orders', 
                    'view'          => $myClass->gen_checkbox('orders'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('orders'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('orders'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('orders'.'_delete'), 
                ],
                [
                    'name'          => 'Customers Management', 
                    'view'          => $myClass->gen_checkbox('users'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('users'.'_view_own'),
                    'create'        => $myClass->gen_checkbox('users'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('users'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('users'.'_delete'), 
                ],
                [
                    'name'          => 'Gateways', 
                    'view'          => $myClass->gen_checkbox('gateways'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('gateways'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('gateways'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('gateways'.'_delete'), 
                ],

                [
                    'name'          => 'Customers', 
                    'view'          => $myClass->gen_checkbox('customers'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('customers'.'_view_own'),
                    'create'        => $myClass->gen_checkbox('customers_create'), 
                    'edit'          => $myClass->gen_checkbox('customers_edit'),  
                    'delete'        => $myClass->gen_checkbox('customers_delete'), 
                ],
                [
                    'name'          => 'Estimates', 
                    'view'          => $myClass->gen_checkbox('estimates'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('estimates'.'_view_own'), 
                    'create'        => $myClass->gen_checkbox('estimates'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('estimates'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('estimates'.'_delete'), 
                ],
                [
                    'name'          => 'Expenses', 
                    'view'          => $myClass->gen_checkbox('expenses'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('expenses'.'_view_own'), 
                    'create'        => $myClass->gen_checkbox('expenses'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('expenses'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('expenses'.'_delete'), 
                ],

                [
                    'name'          => 'Invoices', 
                    'view'          => $myClass->gen_checkbox('invoices'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('invoices'.'_view_own'), 
                    'create'        => $myClass->gen_checkbox('invoices'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('invoices'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('invoices'.'_delete'), 
                ],
                [
                    'name'          => 'Credit Note', 
                    'view'          => $myClass->gen_checkbox('credit_notes'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('credit_notes'.'_view_own'), 
                    'create'        => $myClass->gen_checkbox('credit_notes'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('credit_notes'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('credit_notes'.'_delete'), 
                ],

                [
                    'name'          => 'Items', 
                    'view'          => $myClass->gen_checkbox('items'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('items'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('items'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('items'.'_delete'), 
                ],
                [
                    'name'          => 'Knowledge Base', 
                    'view'          => $myClass->gen_checkbox('Knowledge_base'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('Knowledge_base'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('Knowledge_base'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('Knowledge_base'.'_delete'), 
                ],

                [
                    'name'          => 'Leads', 
                    'view'          => $myClass->gen_checkbox('leads'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('leads'.'_view_own'),  
                    'create'        => $myClass->gen_checkbox('leads'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('leads'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('leads'.'_delete'), 
                ],

                [
                    'name'          => 'Payments', 
                    'view'          => $myClass->gen_checkbox('payments'.'_view'), 
                    'view_own'      => '<i class="fa fa-question-circle mtop15" data-toggle="tooltip" data-title="'.__('form.perm_payment_view_own').'"></i>',  
                    'create'        => $myClass->gen_checkbox('payments'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('payments'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('payments'.'_delete'), 
                ],

                [
                    'name'          => 'Proposals', 
                    'view'          => $myClass->gen_checkbox('proposals'.'_view'), 
                    'view_own'      => $myClass->gen_checkbox('proposals'.'_view_own'), 
                    'create'        => $myClass->gen_checkbox('proposals'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('proposals'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('proposals'.'_delete'), 
                ],

                [
                    'name'          => 'Projects', 
                    // 'view'          => '<i class="fa fa-question-circle text-danger" data-toggle="tooltip" data-title="'.__('form.perm_project_view').'" data-original-title="" title=""></i><br>'.$myClass->gen_checkbox('projects'.'_view'), 
                    'view'          => $myClass->gen_checkbox('projects'.'_view'), 
                    'view_own'      => '<i class="fa fa-question-circle mtop25" data-toggle="tooltip" data-title="'.__('form.perm_project_view_own').'" data-original-title="" title=""></i>', 
                    'create'        => $myClass->gen_checkbox('projects'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('projects'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('projects'.'_delete'), 
                ],

                [
                    'name'          => 'Reports', 
                    'view'          => $myClass->gen_checkbox('reports'.'_view'), 
                    'view_own'      => '', 
                    'create'        => '', 
                    'edit'          => '',   
                    'delete'        => '',  
                ],

                [
                    'name'          => 'Tasks', 
                    'view'          => '<i class="fa fa-question-circle text-danger" data-toggle="tooltip" data-title="'.__('form.perm_task_view').'"></i><br>'.$myClass->gen_checkbox('tasks'.'_view'), 
                    'view_own'      => '<i class="fa fa-question-circle mtop25" data-toggle="tooltip" data-title="'.__('form.perm_task_view_own').'"></i>', 
                    'create'        => $myClass->gen_checkbox('tasks'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('tasks'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('tasks'.'_delete'), 
                ],

                [
                    'name'          => 'Teams', 
                    'view'          => $myClass->gen_checkbox('teams'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('teams'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('teams'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('teams'.'_delete'), 
                ],


                [
                    'name'          => 'Team Members', 
                    'view'          => $myClass->gen_checkbox('team_members'.'_view'), 
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('team_members'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('team_members'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('team_members'.'_delete'), 
                ],

                [
                    'name'          => 'Payment Collection', 
                    'view'          => $myClass->gen_checkbox('collections'.'_view'),
                    'view_own'      => '', 
                    'create'        => $myClass->gen_checkbox('collections'.'_create'),
                    'edit'          => $myClass->gen_checkbox('collections'.'_edit'),
                    'delete'        => $myClass->gen_checkbox('collections'.'_delete'),
                ],
                [
                    'name'          => 'Support Tickets', 
                    'view'          => $myClass->gen_checkbox('tickets'.'_view'),

                    'view'          => '<i class="fa fa-question-circle text-danger" data-toggle="tooltip" data-title="'.__('form.perm_ticket_view').'"></i><br>'.$myClass->gen_checkbox('tickets'.'_view'),

                    'view_own'      => '<i class="fa fa-question-circle mtop25" data-toggle="tooltip" data-title="'.__('form.perm_task_view_own').'"></i>', 
                     
                    'view_own'      => $myClass->gen_checkbox('tickets'.'_view_own'), 
                    'create'        => $myClass->gen_checkbox('tickets'.'_create'), 
                    'edit'          => $myClass->gen_checkbox('tickets'.'_edit'),  
                    'delete'        => $myClass->gen_checkbox('tickets'.'_delete'), 
                ],
             ];

        return $data;
    }


    function set_permissions($permissions) 
    {		
			
		$this->permissions = $permissions;		
	}

	function get_permissions() 
    {		
			
		return $this->permissions;		
	}
}
