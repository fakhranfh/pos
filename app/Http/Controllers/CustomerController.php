<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(
        CustomerService $customerService,

    ) {
        $this->customerService = $customerService;

    }

    private function foreignData()
    {
        return [];
    }

    public function index()
    {
        return view('app.customer.index');
    }

    public function list(Request $request)
    {
        $filters = $request->only(['name', 'phone', 'email', 'created_from', 'created_to']);
        $items = $this->customerService->get($filters);

        return response()->json([
            'data' => $items->map(fn ($item) => [
                'id' => $item->id ?? '',
                'name' => $item->name ?? '',
                'phone' => $item->phone ?? '',
                'email' => $item->email ?? '',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s') ?? '',
                'actions' => [
                    'show' => route('customers.show', $item->id),
                    'edit' => route('customers.edit', $item->id),
                    'delete' => route('customers.destroy', $item->id),
                ],
            ])->toArray(),
        ]);
    }

    public function show($id)
    {
        $item = $this->customerService->find($id);
        $foreignData = $this->foreignData();

        return view('app.customer.show', [
            'item' => $item,
        ] + $foreignData);
    }

    public function create()
    {
        return view('app.customer.create', $this->foreignData());
    }

    public function store(StoreCustomerRequest $request)
    {
        $item = $this->customerService->create($request->validated());

        return redirect()->route('customers.show', $item)->with('success', __('Customers created successfully.'));
    }

    public function edit($id)
    {
        $item = $this->customerService->find($id);
        $foreignData = $this->foreignData();

        return view('app.customer.edit', [
            'item' => $item,
        ] + $foreignData);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $this->customerService->update($id, $request->validated());

        return redirect()->route('customers.show', $id)->with('success', __('Customers updated successfully.'));
    }

    public function destroy($id)
    {
        $this->customerService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('Item deleted successfully.')]);
        }

        return redirect()->route('customers.index')->with('success', __('Customers deleted successfully.'));
    }
}
