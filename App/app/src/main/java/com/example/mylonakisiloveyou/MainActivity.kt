package com.example.mylonakisiloveyou

import android.os.Bundle
import android.webkit.WebSettings
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_main)
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        val webView = findViewById<WebView>(R.id.webview)

        webView.settings.cacheMode = WebSettings.LOAD_NO_CACHE

        // Configurar el WebView para que abra enlaces dentro de la aplicación
        webView.webViewClient = WebViewClient()

        // Habilitar JavaScript si tu página web lo necesita
        webView.settings.javaScriptEnabled = true

        // Cargar un enlace específico
        webView.loadUrl("http://192.168.219.241:8000") // Cambia "https://www.example.com" por el enlace deseado
    }
}