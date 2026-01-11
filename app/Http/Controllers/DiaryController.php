<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    // 一覧表示
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));

        $diaries = Diary::whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->orderBy('date', 'desc')
            ->get();

        // 月選択用のオプション（過去12ヶ月）
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $months[$date->format('Y-m')] = $date->format('Y年n月');
        }

        return view('diaries.index', compact('diaries', 'months', 'month'));
    }

    // 新規作成画面
    public function create()
    {
        return view('diaries.create');
    }

    // 保存
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
        ]);

        Diary::create($validated);

        return redirect()->route('diaries.index')
            ->with('success', '日記を保存しました');
    }

    // 編集画面
    public function edit(Diary $diary)
    {
        return view('diaries.edit', compact('diary'));
    }

    // 更新
    public function update(Request $request, Diary $diary)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
        ]);

        $diary->update($validated);

        return redirect()->route('diaries.index')
            ->with('success', '日記を更新しました');
    }

    // 削除
    public function destroy(Diary $diary)
    {
        $diary->delete();

        return redirect()->route('diaries.index')
            ->with('success', '日記を削除しました');
    }
}