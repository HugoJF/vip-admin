<?php

namespace App\Http\Controllers;

use App\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class TokenController extends Controller
{
    use FormBuilderTrait;

    public function index(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $tokens = Token::query();

            if ($request->has('trashed')) {
                $tokens->withTrashed();
            }
        } else {
            $tokens = Auth::user()->tokens();
        }

        $tokens->with('tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user');

        return view('tokens.index', [
            'tokens'    => $tokens->get(),
            'highlight' => $request->get('highlight'),
        ]);
    }

    public function show(Token $token)
    {
        $token->load(['tokenOrder', 'tokenOrder.baseOrder', 'tokenOrder.baseOrder.user']);

        return view('tokens.show', [
            'token' => $token,
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->has('confirming') || $request->input('confirming') != true) {
            return view('tokens.create');
        }

        $validator = Validator::make($request->all(), [
            'duration'          => 'required|numeric',
            'expiration'        => 'required|numeric',
            'custom-duration'   => 'required_if:duration,-1',
            'custom-expiration' => 'required_if:expiration,-1',
            'note'              => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $duration = $request->input('duration');
        if ($duration == -1) {
            $duration = intval($request->input('custom-duration'));
        }

        $expiration = $request->input('expiration');
        if ($expiration == -1) {
            $expiration = intval($request->input('custom-expiration'));

            if ($expiration === 0) {
                $expiration = 24 * 365;
            }
        }

        $expiration_date = \Carbon\Carbon::now()->addHours($expiration);

        if ($request->has('note')) {
            $note = $request->input('note');
        } else {
            $note = 'Empty';
        }

        return view('tokens.create_confirmation', [
            'duration'        => $duration,
            'expiration'      => $expiration,
            'expiration_date' => $expiration_date,
            'note'            => $note,
        ]);
    }

    public function storeExtra(Request $request)
    {
        $user = Auth::user();

        $generatedTokens = $user->tokens()->count();
        $allowedTokens = $user->allowedTokens();

        if ($generatedTokens >= $allowedTokens) {
            flash()->error(__('messages.controller-token-cannot-generate-extra'));

            return redirect()->back();
        }
        $token = Token::make();

        $token->token = 'extra_token_'.substr(md5(microtime()), 0, \Setting::get('token-size', 15));
        $token->user()->associate(Auth::user());
        $token->duration = \Setting::get('extra-token-duration', 7);
        $token->expiration = \Setting::get('extra-token-expiration', 24 * 7);
        $token->note = __('messages.controller-token-extra-token-note', ['user' => $user->username]);

        $token->save();

        flash()->success(__('messages.controller-token-extra-token-generation-success', ['token' => $token->token]));

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'duration'          => 'required|numeric',
            'expiration'        => 'required|numeric',
            'custom-duration'   => 'required_if:duration,-1',
            'custom-expiration' => 'required_if:expiration,-1',
            'note'              => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tokens.create')->withInput()->withErrors($validator);
        }

        $token = Token::make();

        $token->token = 'token'.substr(md5(microtime()), 0, \Setting::get('token-size', 15));
        // $token->user()->associate(Auth::user());
        $token->duration = $request->input('duration');
        $token->expiration = $request->input('expiration');
        $token->note = $request->input('note');

        $token->save();

        return redirect()->route('tokens.show', $token->token);
    }

    public function edit(Token $token)
    {
        if ($token->status() == 'Used') {
            flash()->error(__('messages.controller-token-cannot-edit-used'));

            return redirect()->back();
        }
        $form = $this->form('App\Forms\TokenForm', [
            'method' => 'PATCH',
            'route'  => ['tokens.update', $token],
            'model'  => $token,
        ]);

        return view('tokens.form', [
            'form' => $form,
        ]);
    }

    public function update(Token $token, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'duration'   => 'numeric',
            'expiration' => 'numeric',
            'note'       => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if ($request->has('token')) {
            $token->token = $request->input('token');
        }

        if ($request->has('duration')) {
            $token->duration = $request->input('duration');
        }

        if ($request->has('expiration')) {
            $token->expiration = $request->input('expiration');
        }

        if ($request->has('note')) {
            $token->note = $request->input('note');
        }

        $token->touch();

        $saved = $token->save();

        if ($saved) {
            flash()->success(__('messages.controller-token-update-success', ['token' => $token->token]));
        } else {
            flash()->error(__('messages.controller-token-update-error', ['token' => $token->token]));
        }

        return redirect()->route('tokens.index');
    }

    public function delete(Token $token)
    {
        $deleted = $token->delete();

        if ($deleted) {
            flash()->success(__('messages.controller-token-delete-success', ['id' => $token->public_id]));
        } else {
            flash()->error(__('messages.controller-token-delete-error', ['id' => $token->public_id]));
        }

        return redirect()->back();
    }

    public function restore(Token $token)
    {
        $restored = $token->restore();

        if ($restored) {
            flash()->success(__('messages.controller-token-restore-success', ['id' => $token->public_id]));
        } else {
            flash()->error(__('messages.controller-token-restore-error', ['id' => $token->public_id]));
        }

        return redirect()->back();
    }
}
