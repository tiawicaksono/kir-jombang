<?php

namespace App\Http\Controllers;

use App\Models\MobileBerita;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BeritaController extends Controller
{
    public function index(): View
    {
        $berita = MobileBerita::query()
            ->where('status', true)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(9);

        $berita->getCollection()->transform(function ($item) {
            $isi = (string) $item->berita;

            // Deteksi URL YouTube yang tersimpan di isi berita.
            preg_match(
                '~(?:https?://)?(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/shorts/)([A-Za-z0-9_-]{11})~i',
                $isi,
                $matches
            );

            $item->youtube_url = null;
            $item->youtube_thumbnail = null;
            $item->youtube_title = null;
            $item->youtube_channel = null;

            if (!empty($matches[1])) {
                $videoId = $matches[1];
                $url = 'https://www.youtube.com/watch?v=' . $videoId;

                $item->youtube_url = $url;
                $item->youtube_thumbnail =
                    'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';

                $metadata = Cache::remember(
                    'youtube_metadata_' . $videoId,
                    now()->addHours(6),
                    function () use ($url) {
                        try {
                            $response = Http::timeout(5)->get(
                                'https://www.youtube.com/oembed',
                                [
                                    'url' => $url,
                                    'format' => 'json',
                                ]
                            );

                            if ($response->successful()) {
                                $data = $response->json();

                                return [
                                    'title' => $data['title'] ?? null,
                                    'channel' => $data['author_name'] ?? 'YouTube',
                                ];
                            }
                        } catch (\Throwable $e) {
                            report($e);
                        }

                        return [
                            'title' => null,
                            'channel' => 'YouTube',
                        ];
                    }
                );

                $item->youtube_title = $metadata['title'];
                $item->youtube_channel = $metadata['channel'];
            }

            // Gambar berita database berupa Base64.
            $gambar = trim((string) $item->gambar);

            if (
                $gambar !== '' &&
                !preg_match('/^data:image\//i', $gambar)
            ) {
                $gambar = 'data:image/jpeg;base64,' .
                    preg_replace('/\s+/', '', $gambar);
            }

            $item->gambar_src = $gambar !== '' ? $gambar : null;

            return $item;
        });

        return view('pages.berita.index', compact('berita'));
    }

    public function show(int $id): View
    {
        $item = MobileBerita::query()
            ->where('status', true)
            ->findOrFail($id);

        $gambar = trim((string) $item->gambar);

        if ($gambar !== '') {
            if (!preg_match('/^data:image\//i', $gambar)) {
                $gambar = 'data:image/jpeg;base64,' .
                    preg_replace('/\s+/', '', $gambar);
            }

            $item->gambar_src = $gambar;
        } else {
            $item->gambar_src = null;
        }

        $beritaLainnya = MobileBerita::query()
            ->where('status', true)
            ->where('id', '!=', $item->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $beritaLainnya->transform(function ($berita) {
            $gambar = trim((string) $berita->gambar);

            if ($gambar !== '') {
                if (!preg_match('/^data:image\//i', $gambar)) {
                    $gambar = 'data:image/jpeg;base64,' .
                        preg_replace('/\s+/', '', $gambar);
                }

                $berita->gambar_src = $gambar;
            } else {
                $berita->gambar_src = null;
            }

            return $berita;
        });

        return view('pages.berita.show', compact('item', 'beritaLainnya'));
    }
}
