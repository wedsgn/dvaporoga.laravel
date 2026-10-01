<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestDeliveryStatus;
use App\Models\RequestProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestProductController extends Controller
{
  public function index(Request $request)
  {
      $user = Auth::user();
      $request_products = $this->query($request)->paginate(50)->appends($request->query());
      return view('admin.request_products.index', compact('request_products', 'user'));
  }

  public function show($id)
  {
      $item = RequestProduct::with('deliveryStatuses')->findOrFail($id);
      $user = Auth::user();
      $data = json_decode($item->data, true);
      $products = [];
      if(isset($data)) {
          foreach ($data as $id) {
              $product = \App\Models\Product::find($id);
              if ($product) {
                  $products[] = $product;
              }
          }
      }
      else {
          $products = [];
      }
      return view('admin.request_products.show', compact('item', 'user', 'products'));
  }
  public function search(Request $request)
  {
      $user = Auth::user();
      $request_products = $this->query($request)->paginate(50)->appends($request->query());
      return view('admin.request_products.index', compact('request_products', 'user'));
  }

  private function query(Request $request)
  {
      $query = RequestProduct::with('deliveryStatuses')->orderBy('id', 'DESC');

      if ($request->filled('search')) {
          $search = $request->input('search');
          $query->where(function ($items) use ($search) {
              $items->where('id', 'ilike', '%' . $search . '%')
                  ->orWhere('phone', 'ilike', '%' . $search . '%')
                  ->orWhere('name', 'ilike', '%' . $search . '%');
          });
      }

      $this->applyDeliveryFilters($query, $request);

      return $query;
  }

  private function applyDeliveryFilters($query, Request $request): void
  {
      if ($request->boolean('delivery_problem')) {
          $query->whereHas('deliveryStatuses', function ($statuses) {
              $this->applyProblemStatusFilter($statuses);
          });
      }

      $channel = $request->input('channel');
      if (!in_array($channel, RequestDeliveryStatus::CHANNELS, true)) {
          return;
      }

      $status = $request->input('status');
      $query->whereHas('deliveryStatuses', function ($statuses) use ($channel, $status) {
          $statuses->where('channel', $channel);

          if ($status === 'problem') {
              $this->applyProblemStatusFilter($statuses);
          } elseif (in_array($status, [
              RequestDeliveryStatus::STATUS_PENDING,
              RequestDeliveryStatus::STATUS_PROCESSING,
              RequestDeliveryStatus::STATUS_SENT,
              RequestDeliveryStatus::STATUS_FAILED,
              RequestDeliveryStatus::STATUS_UNAVAILABLE,
              RequestDeliveryStatus::STATUS_UNCONFIRMED,
          ], true)) {
              $statuses->where('status', $status);
          }
      });
  }

  private function applyProblemStatusFilter($statuses): void
  {
      $pendingCutoff = now()->subMinutes(RequestDeliveryStatus::PENDING_STALE_MINUTES);
      $processingCutoff = now()->subMinutes(RequestDeliveryStatus::PROCESSING_STALE_MINUTES);

      $statuses->where(function ($problem) use ($pendingCutoff, $processingCutoff) {
          $problem
              ->whereIn('status', [
                  RequestDeliveryStatus::STATUS_FAILED,
                  RequestDeliveryStatus::STATUS_UNAVAILABLE,
                  RequestDeliveryStatus::STATUS_UNCONFIRMED,
              ])
              ->orWhere(function ($stalePending) use ($pendingCutoff) {
                  $stalePending
                      ->where('status', RequestDeliveryStatus::STATUS_PENDING)
                      ->where(function ($age) use ($pendingCutoff) {
                          $age->where('updated_at', '<=', $pendingCutoff)
                              ->orWhere(function ($fallback) use ($pendingCutoff) {
                                  $fallback->whereNull('updated_at')
                                      ->where('created_at', '<=', $pendingCutoff);
                              });
                      });
              })
              ->orWhere(function ($staleProcessing) use ($processingCutoff) {
                  $staleProcessing
                      ->where('status', RequestDeliveryStatus::STATUS_PROCESSING)
                      ->where(function ($age) use ($processingCutoff) {
                          $age->where('updated_at', '<=', $processingCutoff)
                              ->orWhere(function ($fallback) use ($processingCutoff) {
                                  $fallback->whereNull('updated_at')
                                      ->where('created_at', '<=', $processingCutoff);
                              });
                      });
              });
      });
  }
}

