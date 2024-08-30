---
title: Roadmap
---

Tempest is still a <span class="hl-attribute">work in progress</span>, this page outlines the next milestones. You're more than welcome to [contribute to Tempest](https://github.com/tempestphp/tempest-framework), you can even work on features in future milestones if anything is of particular interest to you. The best way to get in touch about Tempest development is to [join our Discord server](https://discord.gg/pPhpTGUMPQ).

## 1.0-alpha1

The first alpha version of Tempest will be the first version that's publicly announced. The goal of announcing this version is to get initial reactions from people reading the docs and playing with the framework. This version won't be stable, and there will be breaking changes after it.

Before we can tag this release, there are a [handful of issues](https://github.com/tempestphp/tempest-framework/milestone/2) to be solved still.

- ✅ **Improvements to migrations**
- ✅ **`:elseif` attribute**
- ✅ **Switch all Reflection usages to Tempest's Reflector wrapper**
- ✅ **Support for standalone SQL migrations**
- ✅ **Improved query builder**
- ✅ **Updated documentation**
- ✅ **Static pages support**
- **Improved file support in requests**
- **MySQL and Postgres support**

## 1.0-alpha2

This version will bundle fixes and feedback changes from the first alpha version. Most notably, this version will also support **PHP 8.4 as the minimum**, and use property hooks wherever possible. This is the last big breaking change prior to tagging version 1.0.

Meanwhile, we also want to look into supporting **Tempest Views as a PhpStorm plugin**. It's still unclear whether we will succeed in adding IDE support, but we'll try to have this fixed before the 1.0 release.

## Next alphas

Depending on the feedback from the first two alpha versions, we might opt into adding more alpha versions before 1.0.

## 1.0 and beyond

There's no hard deadline on when Tempest 1.0 should be tagged. It's clear that we'll have to wait until PHP 8.4 has been released, though it could be a couple of months longer still. 

The following features will be included in Tempest 1.0:

- **MVC support**
- **Console application support**
- **ORM and database support**
- **Event bus**
- **Command bus** 
- **Logging**
- **Mapping and validation** 
- **Tempest Views**

Tempest 1.0 will be a lightweight version of the framework, with several useful features still missing. We plan on added features like queue management and mail support in subsequent minor versions:

- 1.1: **auth, htmx support, improved form support, and app events**
- 1.2: **queue manager**
- 1.3: **mail support**
- 1.4: **static site generation**
- 1.5: **event bus improvements, possibly with ES support**