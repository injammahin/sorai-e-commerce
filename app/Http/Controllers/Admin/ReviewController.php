<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
class ReviewController extends Controller {public function index(Request $r){$reviews=Review::with(['product','user'])->when($r->filled('status'),fn($q)=>$q->where('status',$r->status))->latest()->paginate(25)->withQueryString();return view('admin.reviews.index',compact('reviews'));}public function update(Request $r,Review $review){$review->update($r->validate(['status'=>'required|in:pending,approved,rejected']));$stats=$review->product->approvedReviews()->selectRaw('AVG(rating) rating, COUNT(*) count')->first();$review->product->update(['rating'=>$stats->rating?:0,'review_count'=>$stats->count]);return back()->with('success','Review status updated.');}public function destroy(Review $review){$review->delete();return back()->with('success','Review deleted.');}}
