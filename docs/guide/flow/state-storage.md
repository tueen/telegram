# State Storage Drivers

Flow sessions and interactive screen states are persisted between updates using pluggable state storage drivers.

```mermaid
flowchart TD
    subgraph RequestCycle ["Stateful Execution in Stateless PHP"]
        direction TB
        Update["📥 Incoming Telegram Update<br/><i>(chat_id: 100, user_id: 200)</i>"] --> Resolve["🔑 Session Key Resolution<br/><code>100:200</code>"]
        Resolve --> LoadState["📖 1. Load Session<br/><code>$store->get(key)</code>"]
        LoadState --> Hydrate["🧠 Hydrate Flow Instance<br/><i>Restores current step, data & message_id</i>"]
        Hydrate --> Exec["⚙️ 2. Execute Step Method<br/><i>User mutation / step transition</i>"]
        Exec --> SaveState["💾 3. Persist Updated State<br/><code>$store->set(key, state, ttl)</code>"]
    end

    subgraph Drivers ["Pluggable Storage Drivers (StateStoreInterface)"]
        direction TB
        File["📁 FileStateStore<br/><b>Zero-config for Webhooks / FPM</b><br/><i>Atomic file locking (LOCK_EX)</i>"]
        Redis["⚡ RedisStateStore<br/><b>Distributed & Clustered</b><br/><i>High-concurrency microservices</i>"]
        Psr16["🔌 Psr16StateStore<br/><b>Framework Adapters</b><br/><i>Laravel Cache, Symfony, PSR-16</i>"]
        Memory["🧠 MemoryStateStore<br/><b>CLI & Testing</b><br/><i>Ephemeral in-process memory</i>"]
    end

    LoadState <-.-> Drivers
    SaveState -.-> Drivers
```

---

## 💾 Available Storage Drivers

### 1. `FileStateStore` (Default for Webhooks)
Zero-configuration file-based storage for webhook environments (PHP-FPM, Apache). State is safely stored in temporary session files with atomic file locking and automatic expiration.

```php
use Tueen\Telegram\Flow\Storage\FileStateStore;

$bot->setFlowStore(new FileStateStore(directory: sys_get_temp_dir() . '/tueen_flows'));
```

### 2. `MemoryStateStore` (Default for CLI Polling)
In-memory array store. Ultra-fast and optimal for long-running daemon processes or unit test suites.

```php
use Tueen\Telegram\Flow\Storage\MemoryStateStore;

$bot->setFlowStore(new MemoryStateStore());
```

### 3. `RedisStateStore` (High-Concurrency & Distributed)
Native Redis driver supporting Redis clusters, automatic TTL expiration, and cross-server session sharing.

```php
use Tueen\Telegram\Flow\Storage\RedisStateStore;

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$bot->setFlowStore(new RedisStateStore(redis: $redis, prefix: 'tueen:flow:'));
```

### 4. `Psr16StateStore` (Framework Adapters)
Integrates with any PSR-16 compliant cache (Laravel Cache, Symfony Cache, etc.).

```php
use Tueen\Telegram\Flow\Storage\Psr16StateStore;

$bot->setFlowStore(new Psr16StateStore($psr16CachePool));
```
