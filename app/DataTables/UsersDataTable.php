<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\SearchPane;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
	/**
	 * Build the DataTable class.
	 *
	 * @param QueryBuilder $query Results from query() method.
	 */
	public function dataTable(QueryBuilder $query): EloquentDataTable
	{
		return (new EloquentDataTable($query))
			->addColumn('checkbox', '')
			->addColumn('action', 'admin.users.action')
			->editColumn('email', function ($row): string {
				return sprintf('<a href="mailto:%s">%s</a>', $row->email, $row->email);
			})
			->editColumn('phone', function ($row): string {
				return $row->phone
					? sprintf('<a href="https://wa.me/%s" target="_blank">%s</a>', $row->phone, $row->phone)
					: '';
			})
			->editColumn('roles', function ($row): string {
				$roles = [];
				foreach ($row->roles as $role) {
					$roles[] = sprintf('<span class="badge badge-info">%s</span>', $role->name);
				}

				return implode('<br>', $roles);
			})
			->editColumn('avatar', function ($row): string {
				return sprintf('<a href="%s" target="_blank"><img src="%s" width="30"></a>', $row->avatar_url, $row->avatar_url);
			})
			->setRowId('id')
			->rawColumns(['action', 'email', 'phone', 'roles', 'avatar']);
	}

	/**
	 * Get the query source of dataTable.
	 */
	public function query(User $model): QueryBuilder
	{
		return $model->newQuery()->with(['skpd', 'roles'])->select($model->getTable() . '.*');
	}

	/**
	 * Optional method if you want to use the html builder.
	 */
	public function html(): HtmlBuilder
	{
		return $this->builder()
			->setTableId('users-table')
			->columns($this->getColumns())
			->minifiedAjax()
			->selectStyleMultiShift()
			->selectSelector('td:first-child')
			->buttons([
				Button::make([
					'extend' => 'create',
					'text' => 'Create',
					'className' => 'btn-success',
				]),
				Button::make([
					'extend' => 'selectAll',
					'text' => 'Select all',
					'className' => 'btn-primary',
				]),
				Button::make([
					'extend' => 'selectNone',
					'text' => 'Deselect all',
					'className' => 'btn-primary',
				]),
				Button::make([
					'extend' => 'excel',
					'text' => 'Excel',
					'className' => 'btn-secondary',
				]),
				Button::make([
					'extend' => 'colvis',
					'text' => 'Columns',
					'className' => 'btn-secondary',
				]),
				Button::make([
					'extend' => 'deleteSelected',
					'text' => 'Delete selected',
					'className' => 'btn-danger',
				]),
			])
			// ->initComplete(
			// 	"function () {
			// 		this.api()
			// 			.columns()
			// 			.every(function () {
			// 				let column = this;
			// 				let title = column.header().textContent;

			// 				// Create input element
			// 				let input = document.createElement('input');
			// 				input.placeholder = title;
			// 				column.header().replaceChildren(input);

			// 				// Event listener for user input
			// 				input.addEventListener('keyup', () => {
			// 					if (column.search() !== this.value) {
			// 						column.search(input.value).draw();
			// 					}
			// 				});
			// 			});
			// 	}"
			// )
		;
	}

	/**
	 * Get the dataTable columns definition.
	 */
	public function getColumns(): array
	{
		return [
			Column::checkbox('&nbsp;')->width(25),
			Column::make('id')->title('ID')->searchPanes(true),
			Column::computed('avatar')->visible(false)->exportable(false),
			Column::make('name')->title('Nama Lengkap'),
			Column::make('username'),
			Column::make('email'),
			Column::make('email_verified_at')->visible(false),
			Column::make('phone')->title('No. HP'),
			Column::make('address')->title('Alamat')->visible(false),
			Column::make('birth_date')->title('Tgl. Lahir')->visible(false),
			Column::make('skpd.nama', 'skpd.nama')->title('SKPD'),
			Column::make('roles', 'roles.name'),
			Column::make('created_at')->visible(false),
			Column::computed('action', '&nbsp;')->exportable(false)->printable(false),
		];
	}

	/**
	 * Get the filename for export.
	 */
	protected function filename(): string
	{
		return 'Users_' . date('Ymd');
	}
}