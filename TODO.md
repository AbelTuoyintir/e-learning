# TODO

## AI / Ollama connection
- [ ] Confirm Ollama endpoint is reachable from the server/container where Laravel runs.
- [ ] Set `OLLAMA_CLOUD_URL` in `.env` to the reachable Ollama base URL (commonly `http://localhost:11434` or `http://<ollama-host>:11434`).
- [ ] Ensure Ollama is running locally/externally.
- [ ] Restart Laravel app after env/config change.
- [ ] Optionally verify using a simple curl/http call from the same environment.
- [ ] Keep `OPENAI_API_KEY` empty if you want to force Ollama-only, or set it if you want OpenAI primary with Ollama fallback.

